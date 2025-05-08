<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ShippingController extends Controller
{
    protected $apiKey;
    protected $baseUrl;
    protected $originCity;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY', '794a5d197b9cb469ae958ed043ccf921');
        $this->baseUrl = env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter');
        $this->originCity = env('RAJAONGKIR_ORIGIN_CITY', '1'); // Default ke Jakarta jika tidak disetel
    }
    
    /**
     * Default shipping page for cart checkout
     */
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get user's cart items
        $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        
        // If cart is empty, redirect back to cart
        if ($cartItems->count() === 0) {
            return redirect()->route('buyer.cart.index')->with('error', 'Keranjang belanja Anda kosong');
        }
        
        // Calculate cart total
        $cartTotal = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        // Calculate total weight
        $totalWeight = 0;
        foreach($cartItems as $item) {
            $totalWeight += ($item->product->weight * $item->quantity);
        }
        // Ensure minimum weight is 1 gram
        $totalWeight = max(1, $totalWeight);
        
        // Get origin city information
        $originCityId = env('RAJAONGKIR_ORIGIN_CITY', '501');
        $originCityName = $this->getOriginCityName($originCityId);
        
        // Get provinces from RajaOngkir API
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get($this->baseUrl . '/province');
            
            $provinces = $response->successful() ? $response->json()['rajaongkir']['results'] : [];
            
            // If user has province_id, fetch cities for that province
            $cities = [];
            if ($user->province_id) {
                $cityResponse = Http::withHeaders([
                    'key' => $this->apiKey
                ])->get($this->baseUrl . '/city', [
                    'province' => $user->province_id
                ]);
                
                if ($cityResponse->successful()) {
                    $cities = $cityResponse->json()['rajaongkir']['results'];
                }
            }
            
        } catch (\Exception $e) {
            $provinces = [];
            $cities = [];
            // Log the error
            \Log::error('Failed to get data from RajaOngkir: ' . $e->getMessage());
        }

        // Set checkout type for view
        $checkoutType = 'cart';
        
        return view('user.checkout.index', compact('cartItems', 'cartTotal', 'user', 'provinces', 'cities', 'originCityName', 'totalWeight', 'checkoutType'));
    }

    /**
     * Buy Now shipping page
     */
    public function buyNowShipping()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        // Get buy now item from session
        $buyNowItem = Session::get('buy_now');
        
        // If buy now item is not in session, redirect back to products
        if (!$buyNowItem) {
            return redirect()->route('buyer.products.index')->with('error', 'Tidak ada produk yang dipilih untuk dibeli');
        }
        
        // Get product details from database to ensure accurate data
        $product = Product::with('images')->findOrFail($buyNowItem['product_id']);
        
        // Make sure we have current product data
        $buyNowItem['price'] = $product->price;
        $buyNowItem['product_name'] = $product->name;
        $buyNowItem['product_image'] = $product->images->first() ? $product->images->first()->image_path : null;
        
        // Create a collection to mimic cart items format for consistent view handling
        $buyNowItems = collect([$buyNowItem]);
        
        // Calculate total
        $cartTotal = $buyNowItem['price'] * $buyNowItem['quantity'];
        
        // Calculate total weight
        $totalWeight = $product->weight * $buyNowItem['quantity'];
        $totalWeight = max(1, $totalWeight); // Ensure minimum weight is 1 gram
        
        // Get origin city information
        $originCityId = env('RAJAONGKIR_ORIGIN_CITY', '501');
        $originCityName = $this->getOriginCityName($originCityId);
        
        // Get provinces from RajaOngkir API
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get($this->baseUrl . '/province');
            
            $provinces = $response->successful() ? $response->json()['rajaongkir']['results'] : [];
            
            // If user has province_id, fetch cities for that province
            $cities = [];
            if ($user->province_id) {
                $cityResponse = Http::withHeaders([
                    'key' => $this->apiKey
                ])->get($this->baseUrl . '/city', [
                    'province' => $user->province_id
                ]);
                
                if ($cityResponse->successful()) {
                    $cities = $cityResponse->json()['rajaongkir']['results'];
                }
            }
            
        } catch (\Exception $e) {
            $provinces = [];
            $cities = [];
            // Log the error
            \Log::error('Failed to get data from RajaOngkir: ' . $e->getMessage());
        }
        
        // Set checkout type for view
        $checkoutType = 'buy_now';
        
        // Pass buy now items as cartItems for consistent view handling
        $cartItems = $buyNowItems;
        
        return view('user.checkout.index', compact('cartItems', 'cartTotal', 'user', 'provinces', 'cities', 'originCityName', 'totalWeight', 'checkoutType'));
    }

    /**
     * Process buy now checkout
     */
    public function processBuyNowShipping(Request $request)
    {
        $request->validate([
            'province_name' => 'required',
            'city_name' => 'required',
            'postal_code' => 'required',
            'shipping_address' => 'required',
            'shipping_courier' => 'required',
            'shipping_service' => 'required',
            'shipping_cost' => 'required|numeric',
            'recipient_name' => 'required',
            'recipient_phone' => 'required',
            'district' => 'required',
            'village' => 'required',
            'residence_type' => 'required|in:house,apartment,kos,rent'
        ]);
        
        // Get authenticated user
        $user = Auth::user();
        
        // Get buy now item from session
        $buyNowItem = Session::get('buy_now');
        
        // If buy now item is not in session, redirect back
        if (!$buyNowItem) {
            return redirect()->route('buyer.products.index')
                ->with('error', 'Tidak ada produk yang dipilih untuk dibeli');
        }
        
        // Get product details from database to ensure accuracy
        $product = Product::findOrFail($buyNowItem['product_id']);
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Calculate total
            $itemTotal = $product->price * $buyNowItem['quantity'];
            $totalWeight = $product->weight * $buyNowItem['quantity'];
            
            // Create the order
            $order = new Order();
            $order->user_id = $user->id;
            
            // Recipient Information
            $order->recipient_name = $request->recipient_name;
            $order->recipient_phone = $request->recipient_phone;
            
            // Shipping Address Details
            $order->province_name = $request->province_name;
            $order->city_name = $request->city_name;
            $order->district = $request->district;
            $order->village = $request->village;
            $order->postal_code = $request->postal_code;
            $order->shipping_address = $request->shipping_address;
            $order->residence_type = $request->residence_type;
            
            // Shipping Information
            $order->shipping_courier = $request->shipping_courier;
            $order->shipping_service = $request->shipping_service;
            $order->shipping_cost = $request->shipping_cost;
            $order->total_weight = $totalWeight;
            
            // Financial Details
            $order->total_amount = $itemTotal + $request->shipping_cost;
            $order->status = 'pending';
            $order->payment_method = null;
            
            $order->save();
            
            // Create order item for the buy now product
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->quantity = $buyNowItem['quantity'];
            $orderItem->price = $product->price;
            $orderItem->subtotal = $buyNowItem['quantity'] * $product->price;
            $orderItem->created_at = now();
            $orderItem->updated_at = now();
            $orderItem->save();
            
            // Log activity
            \Log::info('Buy Now order created: Order #' . $order->id . ', Product #' . $product->id);
            
            // Clear buy now session after processing
            Session::forget('buy_now');
            
            DB::commit();
            
            // Redirect to payment page
            return redirect()->route('buyer.payment', $order->id)
                ->with('success', 'Order berhasil dibuat. Silakan selesaikan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error dalam processBuyNowShipping: ' . $e->getMessage());
            return redirect()->route('buyer.buy-now.shipping')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get origin city name based on city ID
     */
    private function getOriginCityName($cityId)
    {
        // Default city mapping if API call fails
        $cityNames = [
            '151' => 'Jakarta',
            '152' => 'Jakarta Pusat',
            '153' => 'Jakarta Barat',
            '154' => 'Jakarta Selatan',
            '155' => 'Jakarta Timur',
            '501' => 'Jakarta Utara',
            '114' => 'Bandung',
            '444' => 'Semarang',
            '133' => 'Surabaya',
            '1' => 'Bali',
            '256' => 'Yogyakarta',
            '22' => 'Medan',
        ];
        
        // Try to get city name from RajaOngkir API
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get($this->baseUrl . '/city', [
                'id' => $cityId
            ]);
            
            if ($response->successful() && 
                isset($response->json()['rajaongkir']['results']) &&
                !empty($response->json()['rajaongkir']['results'])) {
                
                $cityData = $response->json()['rajaongkir']['results'];
                if (is_array($cityData)) {
                    return $cityData[0]['city_name'] . ' (' . $cityData[0]['province'] . ')';
                } else {
                    return $cityData['city_name'] . ' (' . $cityData['province'] . ')';
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to get origin city name from RajaOngkir: ' . $e->getMessage());
        }
        
        // Fallback to local mapping
        return $cityNames[$cityId] ?? 'Indonesia';
    }
    
    /**
     * Get cities by province ID
     */
    public function getCities(Request $request)
    {
        $provinceId = $request->province_id;
        
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->get($this->baseUrl . '/city', [
                'province' => $provinceId
            ]);
            
            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'cities' => $response->json()['rajaongkir']['results']
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get cities'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
    
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'destination' => 'required',
            'weight' => 'required|numeric|min:1',
            'courier' => 'required|in:jne,pos,tiki'
        ]);
        
        // Untuk debugging
        \Log::info('Permintaan kalkulasi pengiriman:', [
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier
        ]);
        
        // Log untuk memastikan kota asal diambil dengan benar
        \Log::info('Menggunakan kota asal (origin):', ['city_id' => $this->originCity]);
        
        try {
            // Membuat payload permintaan
            $payload = [
                'origin' => $this->originCity,
                'destination' => $request->destination,
                'weight' => (int)$request->weight,
                'courier' => $request->courier
            ];
            
            \Log::info('Permintaan API RajaOngkir:', $payload);
            
            // Gunakan metode form parameter untuk mengirim permintaan ke RajaOngkir
            $response = Http::asForm()->withHeaders([
                'key' => $this->apiKey
            ])->post($this->baseUrl . '/cost', $payload);
            
            $responseBody = $response->body();
            \Log::info('Respons mentah API RajaOngkir: ' . $responseBody);
            
            if ($response->successful()) {
                $parsedResponse = $response->json();
                \Log::info('Respons sukses API RajaOngkir:', $parsedResponse);
                
                if (isset($parsedResponse['rajaongkir']['results']) && 
                    is_array($parsedResponse['rajaongkir']['results']) && 
                    count($parsedResponse['rajaongkir']['results']) > 0 &&
                    isset($parsedResponse['rajaongkir']['results'][0]['costs']) &&
                    !empty($parsedResponse['rajaongkir']['results'][0]['costs'])) {
                    
                    return response()->json([
                        'success' => true,
                        'shipping_options' => $parsedResponse['rajaongkir']['results'][0]['costs']
                    ]);
                } else {
                    \Log::warning('API RajaOngkir mengembalikan array hasil kosong');
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada layanan pengiriman yang tersedia untuk tujuan ini.'
                    ]);
                }
            }
            
            \Log::error('Respons gagal API RajaOngkir: ' . $responseBody);
            
            // Ekstrak pesan error dari respons jika ada
            $errorMessage = 'Gagal menghitung biaya pengiriman';
            if (isset($parsedResponse['rajaongkir']['status']['description'])) {
                $errorMessage .= ': ' . $parsedResponse['rajaongkir']['status']['description'];
            }
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ]);
        } catch (\Exception $e) {
            \Log::error('Pengecualian dalam calculateShipping: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    public function processShipping(Request $request)
    {
        $request->validate([
            'province_name' => 'required',
            'city_name' => 'required',
            'postal_code' => 'required',
            'shipping_address' => 'required',
            'shipping_courier' => 'required',
            'shipping_service' => 'required',
            'shipping_cost' => 'required|numeric',
            'recipient_name' => 'required',
            'recipient_phone' => 'required',
            'district' => 'required',
            'village' => 'required',
            'residence_type' => 'required|in:house,apartment,kos,rent'
        ]);
        
        // Get authenticated user
        $user = Auth::user();
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Get cart items
            $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
            
            // Calculate cart total
            $cartTotal = $cartItems->sum(function($item) {
                return $item->quantity * $item->price;
            });
            
            // Calculate total weight
            $totalWeight = $cartItems->sum(function($item) {
                return $item->product->weight * $item->quantity;
            });
            
            // Create the order
            $order = new Order();
            $order->user_id = $user->id;
            
            // Recipient Information
            $order->recipient_name = $request->recipient_name;
            $order->recipient_phone = $request->recipient_phone;
            
            // Shipping Address Details
            $order->province_name = $request->province_name;
            $order->city_name = $request->city_name;
            $order->district = $request->district;
            $order->village = $request->village;
            $order->postal_code = $request->postal_code;
            $order->shipping_address = $request->shipping_address;
            $order->residence_type = $request->residence_type;
            
            // Shipping Information
            $order->shipping_courier = $request->shipping_courier;
            $order->shipping_service = $request->shipping_service;
            $order->shipping_cost = $request->shipping_cost;
            $order->total_weight = $totalWeight;
            
            // Financial Details
            $order->total_amount = $cartTotal + $request->shipping_cost;
            $order->status = 'pending';
            $order->payment_method = null;
            
            $order->save();
            
            // TAMBAHKAN KODE BERIKUT: Simpan item-item pesanan ke dalam tabel order_items
            foreach ($cartItems as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->product_id;
                $orderItem->quantity = $item->quantity;
                $orderItem->price = $item->price;
                $orderItem->subtotal = $item->quantity * $item->price;
                $orderItem->created_at = now(); // Menambahkan datetime
                $orderItem->updated_at = now();
                $orderItem->save();
                
                // Catat aktivitas
                \Log::info('Item pesanan ditambahkan: Order #' . $order->id . ', Produk #' . $item->product_id);
            }
            
            // Delete cart items
            Cart::where('user_id', $user->id)->delete();
            
            DB::commit();
            
            // Redirect to payment page
            return redirect()->route('buyer.payment', $order->id)
                ->with('success', 'Order berhasil dibuat. Silakan selesaikan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error dalam processShipping: ' . $e->getMessage());
            return redirect()->route('buyer.shipping')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}