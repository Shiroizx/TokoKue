<?php
namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $seller_id = $user->id;
        $shop = Shop::where('user_id', $user->id)->first();
        
        // Mendapatkan produk yang dimiliki oleh seller berdasarkan seller_id
        $sellerProductIds = Product::where('seller_id', $seller_id)->pluck('id')->toArray();
        
        // Jika tidak ada produk, buat empty pagination
        if (empty($sellerProductIds)) {
            $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // empty collection
                0, // total items
                10, // per page
                1, // current page
                ['path' => request()->url()] // options
            );
            
            return view('seller.orders.index', [
                'orders' => $emptyPaginator,
                'shop' => $shop
            ])->with('warning', 'You have no products to sell yet!');
        }
        
        // Filter berdasarkan status dan pencarian jika disediakan
        $status = $request->input('status');
        $search = $request->input('search');
        
        // Query untuk mendapatkan order yang mengandung produk dari seller
        $query = Order::whereHas('items', function($query) use ($sellerProductIds) {
            $query->whereIn('product_id', $sellerProductIds);
        })->with(['user', 'items' => function($query) use ($sellerProductIds) {
            $query->whereIn('product_id', $sellerProductIds);
        }, 'items.product']);
        
        // Apply filters
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }
        
        // Get paginated orders
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('seller.orders.index', compact('orders', 'shop'));
    }
    
    /**
     * Display the specified order.
     */
    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $seller_id = $user->id;
        $shop = Shop::where('user_id', $user->id)->first();
        
        // Mendapatkan produk yang dimiliki oleh seller
        $sellerProductIds = Product::where('seller_id', $seller_id)->pluck('id')->toArray();
        
        // Mencari order berdasarkan ID
        $order = Order::with(['user', 'items' => function($query) use ($sellerProductIds) {
            $query->whereIn('product_id', $sellerProductIds);
        }, 'items.product'])->findOrFail($id);
        
        // Periksa apakah order memiliki produk seller
        if ($order->items->isEmpty()) {
            abort(404, 'Order not found or contains no products from your shop.');
        }
        
        // Dapatkan subtotal dari produk-produk seller dalam order ini
        $sellerSubtotal = $order->items->sum(function($item) {
            return $item->price * $item->quantity;
        });
        
        return view('seller.orders.detail', compact('order', 'shop', 'sellerSubtotal'));
    }
    
    /**
     * Update the order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        $seller_id = $user->id;
        $shop = Shop::where('user_id', $user->id)->first();
        
        // Mendapatkan produk yang dimiliki oleh seller
        $sellerProductIds = Product::where('seller_id', $seller_id)->pluck('id')->toArray();
        
        // Mencari order berdasarkan ID dan memastikan order mengandung produk dari seller
        $order = Order::whereHas('items', function($query) use ($sellerProductIds) {
            $query->whereIn('product_id', $sellerProductIds);
        })->findOrFail($id);
        
        // Validate status
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);
        
        // Cek status sebelumnya untuk mengetahui apakah ini adalah perubahan ke "delivered"
        $previousStatus = $order->status;
        $newStatus = $validated['status'];
        
        // Start database transaction
        DB::beginTransaction();
        
        try {
            // Update the order status
            $order->status = $newStatus;
            
            // Update timestamps berdasarkan status
            if ($newStatus == 'shipped' && !$order->shipped_at) {
                $order->shipped_at = now();
            } elseif ($newStatus == 'delivered' && !$order->delivered_at) {
                $order->delivered_at = now();
                
                // Jika status berubah menjadi "delivered", kurangi stok produk
                if ($previousStatus != 'delivered') {
                    $this->decreaseProductStock($order, $sellerProductIds);
                }
            } elseif ($newStatus == 'cancelled' && !$order->canceled_at) {
                $order->canceled_at = now();
            }
            
            $order->save();
            
            // Commit transaction
            DB::commit();
            
            return redirect()->route('seller.orders.show', $order)
                ->with('success', 'Order status updated successfully!');
                
        } catch (\Exception $e) {
            // Rollback transaction jika terjadi error
            DB::rollBack();
            
            // Log error
            Log::error('Failed to update order status: ' . $e->getMessage(), [
                'order_id' => $id,
                'seller_id' => $seller_id,
                'status' => $newStatus,
                'exception' => $e
            ]);
            
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }
    
    /**
     * Decrease product stock when order is set to delivered.
     */
    private function decreaseProductStock($order, $sellerProductIds)
    {
        // Ambil item order yang hanya milik seller ini
        $orderItems = OrderItem::where('order_id', $order->id)
            ->whereIn('product_id', $sellerProductIds)
            ->with('product')
            ->get();
            
        // Log untuk debug
        Log::info('Decreasing stock for delivered order', [
            'order_id' => $order->id,
            'item_count' => $orderItems->count(),
        ]);
            
        foreach ($orderItems as $item) {
            // Pastikan product ada
            if (!$item->product) {
                Log::warning('Product not found for order item', [
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'product_id' => $item->product_id
                ]);
                continue;
            }
            
            // Kurangi stok dengan atomic update untuk mencegah race condition
            $updated = DB::table('products')
                ->where('id', $item->product_id)
                ->where('seller_id', Auth::id()) // Pastikan hanya produk seller ini yang diupdate
                ->update([
                    'stock' => DB::raw('GREATEST(stock - ' . $item->quantity . ', 0)') // Pastikan stok tidak negatif
                ]);
                
            if ($updated) {
                // Refresh product untuk mendapatkan stok terbaru
                $item->product->refresh();
                
                Log::info('Stock decreased for product', [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity_purchased' => $item->quantity,
                    'remaining_stock' => $item->product->stock
                ]);
                
                // Periksa jika stok mendekati habis
                if ($item->product->stock <= 5) {
                    Log::warning('Low stock alert', [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'remaining_stock' => $item->product->stock
                    ]);
                    
                    // Di sini bisa ditambahkan logic untuk mengirim notifikasi low stock ke seller
                }
            } else {
                Log::warning('Failed to decrease stock for product', [
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity
                ]);
            }
        }
    }
    
    /**
     * Update the tracking number.
     */
    public function updateTracking(Request $request, $id)
    {
        $user = Auth::user();
        $seller_id = $user->id;
        $shop = Shop::where('user_id', $user->id)->first();
        
        // Mendapatkan produk yang dimiliki oleh seller
        $sellerProductIds = Product::where('seller_id', $seller_id)->pluck('id')->toArray();
        
        // Mencari order berdasarkan ID dan memastikan order mengandung produk dari seller
        $order = Order::whereHas('items', function($query) use ($sellerProductIds) {
            $query->whereIn('product_id', $sellerProductIds);
        })->findOrFail($id);
        
        // Validate tracking number
        $validated = $request->validate([
            'tracking_number' => 'nullable|string|max:255',
        ]);
        
        // Update the order
        $order->tracking_number = $validated['tracking_number'];
        $order->save();
        
        return redirect()->route('seller.orders.show', $order)
            ->with('success', 'Tracking number updated successfully!');
    }
}