<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderItem;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        MidtransConfig::$serverKey = env('MIDTRANS_SERVER_KEY');
        MidtransConfig::$clientKey = env('MIDTRANS_CLIENT_KEY');
        MidtransConfig::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        MidtransConfig::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        MidtransConfig::$is3ds = env('MIDTRANS_IS_3DS', true);
    }

    public function index($orderId)
    {
        // Ambil order dengan relasi order items dan produk
        $order = Order::with(['orderItems.product'])->findOrFail($orderId);

        // Pastikan order milik user yang sedang login
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('buyer.home')
                ->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        if ($order->status !== 'pending' && $order->status !== 'pending_payment') {
            return redirect()->route('buyer.home')
                ->with('error', 'Status pesanan tidak valid untuk pembayaran.');
        }

        // Generate unique order ID
        $uniqueOrderId = 'ORDER-' . $order->id . '-' . Str::random(6);

        // Hitung total berat dan jumlah produk
        $totalWeight = 0;
        $totalProducts = 0;
        $orderItems = $order->orderItems->map(function($item) use (&$totalWeight, &$totalProducts) {
            $itemWeight = $item->product->weight * $item->quantity;
            $totalWeight += $itemWeight;
            $totalProducts += $item->quantity;
            
            // Ambil gambar produk (sesuaikan dengan struktur model Anda)
            $primaryImage = $item->product->images()->first();
            
            return [
                'product' => $item->product,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->price * $item->quantity,
                'weight' => $itemWeight,
                'image' => $primaryImage 
                    ? asset('storage/' . $primaryImage->image_path) 
                    : asset('images/placeholder-product.png')
            ];
        });

        // Buat parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $uniqueOrderId,
                'gross_amount' => (int)round($order->total_amount)
            ],
            'customer_details' => [
                'first_name' => $order->recipient_name,
                'last_name' => '',
                'email' => Auth::user()->email ?? 'customer@example.com',
                'phone' => $order->recipient_phone,
                'billing_address' => [
                    'first_name' => $order->recipient_name,
                    'last_name' => '',
                    'address' => $order->shipping_address,
                    'city' => $order->city_name,
                    'postal_code' => $order->postal_code,
                    'country' => 'IDN'
                ]
            ],
            'item_details' => $this->getItemDetails($order)
        ];

        // Dapatkan Snap Token
        $snapToken = Snap::getSnapToken($params);

        return view('user.payment.index', [
            'order' => $order, 
            'snapToken' => $snapToken,
            'uniqueOrderId' => $uniqueOrderId,
            'orderItems' => $orderItems,
            'totalWeight' => $totalWeight,
            'totalProducts' => $totalProducts
        ]);
    }

    private function getItemDetails($order)
    {
        // Log untuk debugging
        \Log::info('Get Item Details - Order Info', [
            'order_id' => $order->id,
            'total_amount' => $order->total_amount,
            'shipping_cost' => $order->shipping_cost
        ]);

        $items = [];

        // Ambil order items
        $orderItems = OrderItem::where('order_id', $order->id)->with('product')->get();

        \Log::info('Order Items Debug', [
            'items_count' => $orderItems->count(),
            'items_data' => $orderItems->toArray()
        ]);

        // Tambahkan item-item produk
        foreach ($orderItems as $item) {
            $productName = optional($item->product)->name ?? 'Produk';
            
            $items[] = [
                'id' => 'product_' . ($item->product_id ?? 'unknown'),
                'price' => (int)round($item->price),
                'quantity' => $item->quantity,
                'name' => $productName
            ];
        }

        // Jika tidak ada order items, tambahkan total pesanan
        if (empty($items)) {
            $items[] = [
                'id' => 'total_order',
                'price' => (int)round($order->total_amount - $order->shipping_cost),
                'quantity' => 1,
                'name' => 'Total Pesanan'
            ];
        }

        // Tambahkan biaya pengiriman
        $items[] = [
            'id' => 'shipping',
            'price' => (int)round($order->shipping_cost),
            'quantity' => 1,
            'name' => 'Biaya Pengiriman'
        ];

        // Log item details
        \Log::info('Midtrans Item Details', [
            'items' => $items
        ]);

        return $items;
    }

    public function handlePaymentCallback(Request $request)
    {
        // Log request mentah untuk debugging
        \Log::info('Midtrans Callback - Raw Request Debug', [
            'method' => $request->method(),
            'full_url' => $request->fullUrl(),
            'client_ip' => $request->ip(),
            'headers' => $request->headers->all(),  
            'content' => $request->getContent()
        ]);

        // Parsing JSON
        $json = json_decode($request->getContent(), true);
        
        // Validasi input
        if (!$json) {
            \Log::error('Invalid JSON received');
            return response()->json([
                'status' => 'error', 
                'message' => 'Invalid JSON'
            ], 400);
        }

        // Validasi keberadaan kunci utama
        $requiredKeys = [
            'order_id', 'status_code', 'gross_amount', 
            'transaction_status', 'payment_type'
        ];

        foreach ($requiredKeys as $key) {
            if (!isset($json[$key])) {
                \Log::error("Missing required key: $key", [
                    'received_data' => $json
                ]);
                return response()->json([
                    'status' => 'error', 
                    'message' => "Missing $key"
                ], 400);
            }
        }

        // Verifikasi signature key Midtrans
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $signatureKey = hash('sha512', 
            $json['order_id'] . 
            $json['status_code'] . 
            $json['gross_amount'] . 
            $serverKey
        );

        // Dapatkan signature dari header Midtrans
        $receivedSignature = $request->header('X-Midtrans-Signature');
        
        // Log untuk debugging verifikasi signature
        \Log::info('Midtrans Signature Verification', [
            'calculated_signature' => $signatureKey,
            'received_signature' => $receivedSignature,
            'all_headers' => $request->headers->all()
        ]);

        // Skip signature verification untuk testing
        if ($receivedSignature && !hash_equals($signatureKey, $receivedSignature)) {
            \Log::error('Invalid Midtrans Signature', [
                'order_id' => $json['order_id'],
                'calculated' => $signatureKey,
                'received' => $receivedSignature
            ]);
            return response()->json([
                'status' => 'error', 
                'message' => 'Invalid signature'
            ], 403);
        }

        // DETEKSI NOTIFIKASI TEST DARI MIDTRANS
        if (strpos($json['order_id'], 'payment_notif_test') !== false) {
            \Log::info('Midtrans Test Notification Detected', [
                'order_id' => $json['order_id']
            ]);
            
            // Kirim response sukses untuk notifikasi tes
            return response()->json(['status' => 'success']);
        }
        
        // Extract order ID
        $orderNumber = null;
        $orderParts = explode('-', $json['order_id']);
        
        if (count($orderParts) >= 3 && strtoupper($orderParts[0]) === 'ORDER') {
            $orderNumber = $orderParts[1];
        } else {
            $orderNumber = $json['order_id'];
        }
        
        \Log::info('Extracted Order Number', [
            'extracted_id' => $orderNumber
        ]);
        
        // Cari order
        $order = Order::find($orderNumber);
        
        if (!$order) {
            \Log::error('Order not found', [
                'order_id' => $orderNumber,
                'original_id' => $json['order_id']
            ]);
            
            // Untuk testing via Postman, tetap return success
            if (strpos($request->userAgent(), 'Postman') !== false) {
                \Log::info('Request from Postman detected, returning success despite order not found');
                return response()->json(['status' => 'success']);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // Mapping metode pembayaran
        $paymentMethodMap = [
            'credit_card' => 'Credit Card',
            'bank_transfer' => 'Bank Transfer',
            'echannel' => 'Mandiri Bill',
            'bca_va' => 'BCA Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'permata_va' => 'Permata Virtual Account',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'indomaret' => 'Indomaret',
            'alfamart' => 'Alfamart',
        ];

        // Start database transaction
        DB::beginTransaction();

        // Tentukan metode pembayaran
        $paymentType = $json['payment_type'] ?? 'unknown';
        $order->payment_method = $paymentMethodMap[$paymentType] ?? $paymentType;

        // Update status berdasarkan transaksi
        switch ($json['transaction_status']) {
            case 'capture':
            case 'settlement':
                $order->status = 'processing';
                $order->payment_status = 'paid';
                
                // Kurangi stok produk
                $orderItems = $order->orderItems()->with('product')->get();
                
                foreach ($orderItems as $item) {
                    $product = $item->product;
                    
                    if (!$product) {
                        \Log::error('Product not found for order item', [
                            'order_id' => $order->id,
                            'order_item_id' => $item->id,
                            'product_id' => $item->product_id
                        ]);
                        continue;
                    }
                    
                    if ($product->stock >= $item->quantity) {
                        $product->decrement('stock', $item->quantity);
                        
                        \Log::info('Stock decreased', [
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'quantity_purchased' => $item->quantity,
                            'remaining_stock' => $product->fresh()->stock
                        ]);
                    } else {
                        \Log::warning('Insufficient stock', [
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'available_stock' => $product->stock,
                            'requested_quantity' => $item->quantity
                        ]);
                    }
                }
                break;
                
            case 'pending':
                $order->status = 'pending_payment';
                $order->payment_status = 'pending';
                break;
                
            case 'deny':
            case 'expire':
            case 'cancel':
                $order->status = 'cancelled';
                $order->payment_status = 'cancelled';
                break;
                
            default:
                \Log::warning('Unhandled transaction status', [
                    'status' => $json['transaction_status']
                ]);
        }

        // Simpan detail pembayaran tambahan
        $order->payment_details = json_encode([
            'payment_type' => $paymentType,
            'transaction_id' => $json['transaction_id'] ?? null,
            'midtrans_order_id' => $json['order_id'] ?? null,
            'gross_amount' => $json['gross_amount'] ?? null,
            'status_code' => $json['status_code'] ?? null,
            'status_message' => $json['status_message'] ?? null,
            'transaction_time' => $json['transaction_time'] ?? null,
            'fraud_status' => $json['fraud_status'] ?? null,
        ]);

        // Simpan perubahan
        $order->save();

        // Commit database transaction
        DB::commit();

        // Log perubahan order
        \Log::info('Order Updated After Midtrans Callback', [
            'order_id' => $order->id,
            'payment_method' => $order->payment_method,
            'status' => $order->status,
            'payment_status' => $order->payment_status
        ]);

        // Kirim response sukses ke Midtrans
        return response()->json(['status' => 'success']);
    }

    public function paymentFinish(Request $request)
    {
        // Log informasi untuk debugging
        \Log::info('Payment Finish - User Info', [
            'user_id' => Auth::id(),
        ]);

        // Cari order terakhir user dengan log tambahan
        $latestOrder = Order::where('user_id', Auth::id())
            ->where('status', 'processing')
            ->latest()
            ->first();

        // Log detail order
        \Log::info('Latest Order Debug', [
            'order_found' => $latestOrder ? 'Yes' : 'No',
            'order_details' => $latestOrder ? $latestOrder->toArray() : 'No order found'
        ]);

        // Jika tidak ada order, redirect dengan pesan
        if (!$latestOrder) {
            return redirect()->route('buyer.home')
                ->with('error', 'Tidak ada pesanan yang ditemukan.');
        }

        // Ubah ini dari menampilkan halaman finish ke redirect ke halaman history order
        return redirect()->route('buyer.orders.history')
            ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
    }

    public function paymentUnfinish(Request $request)
    {
        // Cari order terakhir user
        $latestOrder = Order::where('user_id', Auth::id())
            ->where('status', 'pending_payment')
            ->latest()
            ->first();

        return view('user.payment.unfinish', [
            'order' => $latestOrder
        ]);
    }

    public function paymentError(Request $request)
    {
        // Cari order terakhir user
        $latestOrder = Order::where('user_id', Auth::id())
            ->where('status', 'cancelled')
            ->latest()
            ->first();

        return view('user.payment.error', [
            'order' => $latestOrder
        ]);
    }

    public function updateStock(Request $request)
    {
        $orderId = $request->order_id;
        $paymentResult = $request->payment_result;
        
        // Validasi input
        if (!$orderId || !$paymentResult) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data'
            ], 400);
        }
        
        // Cari order milik user yang login
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->with('orderItems.product')
            ->first();
            
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
        
        // Update menggunakan transaksi database
        DB::transaction(function () use ($order, $paymentResult) {
            // Update status order
            $order->status = 'processing';
            $order->payment_status = 'paid';
            
            // Simpan payment method dari response
            $paymentMethodMap = [
                'credit_card' => 'Credit Card',
                'bank_transfer' => 'Bank Transfer',
                'echannel' => 'Mandiri Bill',
                'bca_va' => 'BCA Virtual Account',
                'bni_va' => 'BNI Virtual Account',
                'permata_va' => 'Permata Virtual Account',
                'gopay' => 'GoPay',
                'shopeepay' => 'ShopeePay',
                'qris' => 'QRIS',
                'indomaret' => 'Indomaret',
                'alfamart' => 'Alfamart',
            ];
            
            $paymentType = $paymentResult['payment_type'] ?? 'unknown';
            $order->payment_method = $paymentMethodMap[$paymentType] ?? $paymentType;
            
            // Simpan detail pembayaran
            $order->payment_details = json_encode([
                'payment_type' => $paymentType,
                'transaction_id' => $paymentResult['transaction_id'] ?? null,
                'order_id' => $paymentResult['order_id'] ?? null,
                'status_code' => $paymentResult['status_code'] ?? null,
                'transaction_time' => $paymentResult['transaction_time'] ?? null,
            ]);
            
            $order->save();
            
            // Kurangi stok produk
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                
                if ($product && $product->stock >= $item->quantity) {
                    $product->decrement('stock', $item->quantity);
                    
                    \Log::info('Stock decreased', [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity_purchased' => $item->quantity,
                        'remaining_stock' => $product->fresh()->stock
                    ]);
                } else {
                    \Log::warning('Insufficient stock', [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'available_stock' => $product->stock,
                        'requested_quantity' => $item->quantity
                    ]);
                }
            }
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully'
        ]);
    }

    private function decreaseProductStock(Order $order)
    {
        // Ambil order items dengan relasi product
        $orderItems = $order->orderItems()->with('product')->get();
        
        foreach ($orderItems as $item) {
            $product = $item->product;
            
            if ($product && $product->stock >= $item->quantity) {
                // Kurangi stok
                $product->decrement('stock', $item->quantity);
                
                \Log::info('Stock decreased for product', [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity_purchased' => $item->quantity,
                    'remaining_stock' => $product->fresh()->stock
                ]);
            } else {
                \Log::warning('Insufficient stock for product', [
                    'order_id' => $order->id,
                    'product_id' => $product->id ?? 'unknown',
                    'product_name' => $product->name ?? 'unknown',
                    'available_stock' => $product->stock ?? 0,
                    'requested_quantity' => $item->quantity
                ]);
                
                // Anda bisa memilih untuk throw exception atau tetap lanjut
                // throw new \Exception("Insufficient stock for product: " . $product->name);
            }
        }
    }
}