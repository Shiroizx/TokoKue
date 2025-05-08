<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Menambahkan item ke keranjang (tabel carts)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addToCart(Request $request)
    {
        // Validasi input dari request
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return back()->with('error', $validator->errors()->first());
        }

        // Ambil data produk berdasarkan ID
        $product = Product::findOrFail($request->product_id);
        
        // Cek ketersediaan stok produk
        if ($product->stock < $request->quantity) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ], 422);
            }
            return back()->with('error', 'Stok tidak mencukupi');
        }
        
        // Cek konflik seller
        $sellerCheck = $this->checkSellerConflict($request->product_id);
        
        // Jika ada konflik seller dan bukan request untuk mengganti keranjang
        if ($sellerCheck['conflict'] && !$request->has('replace_cart')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'conflict' => true,
                    'message' => 'Di keranjang Anda terdapat produk dari ' . $sellerCheck['seller_name'] . ' dan Anda tidak bisa memilih produk dari toko yang berbeda',
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ], 409); // Conflict status code
            }
            
            // Jika bukan AJAX, simpan data ke session untuk digunakan di view
            return back()->with([
                'seller_conflict' => true,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'seller_name' => $sellerCheck['seller_name']
            ]);
        }
        
        // Jika ada konflik seller dan user memilih untuk mengganti keranjang
        if ($request->has('replace_cart') && $request->replace_cart == 'yes') {
            // Hapus semua item di keranjang
            $userId = Auth::check() ? Auth::id() : null;
            $sessionId = Auth::check() ? null : Session::getId();
            
            Cart::when($userId, function($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })
                ->when($sessionId, function($query) use ($sessionId) {
                    return $query->where('session_id', $sessionId);
                })
                ->delete();
        }
        
        // Proses menambahkan item ke keranjang
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = Auth::check() ? null : Session::getId();
        
        // Cek apakah produk sudah ada di keranjang
        $existingItem = Cart::where('product_id', $product->id)
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($sessionId, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId);
            })
            ->first();
        
        if ($existingItem) {
            // Jika produk sudah ada, update jumlahnya
            $newQuantity = $existingItem->quantity + $request->quantity;
            
            // Cek stok kembali setelah menambahkan quantity
            if ($product->stock < $newQuantity) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk menambah jumlah'
                    ], 422);
                }
                return back()->with('error', 'Stok tidak mencukupi untuk menambah jumlah');
            }
            
            // Update quantity produk di keranjang
            $existingItem->quantity = $newQuantity;
            $existingItem->save();
        } else {
            // Jika produk belum ada, tambahkan sebagai item baru
            Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);
        }
        
        // Handle berbagai jenis respons berdasarkan tipe request
        if ($request->ajax()) {
            // Hitung jumlah item di keranjang untuk respons AJAX
            $cartCount = Cart::when($userId, function($query) use ($userId) {
                    return $query->where('user_id', $userId);
                })
                ->when($sessionId, function($query) use ($sessionId) {
                    return $query->where('session_id', $sessionId);
                })
                ->sum('quantity');
            
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang',
                'cartCount' => $cartCount
            ]);
        }
        
        // Respons standar untuk request non-AJAX
        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }
    
    /**
     * Menampilkan daftar item di keranjang
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = Auth::check() ? null : Session::getId();
        
        // Ambil semua item di keranjang
        $cartItems = Cart::with('product')
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($sessionId, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId);
            })
            ->get();
            
        // Hitung total keranjang
        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        return view('user.cart.index', compact('cartItems', 'total'));
    }
    
    /**
     * Menggabungkan keranjang dari session ke user saat login
     * Metode ini bisa dipanggil dari AuthController setelah login berhasil
     *
     * @param string $sessionId
     * @param int $userId
     * @return void
     */
    public static function mergeSessionCartToUser($sessionId, $userId)
    {
        // Ambil semua item di keranjang berdasarkan session_id
        $sessionCartItems = Cart::where('session_id', $sessionId)->get();
        
        foreach ($sessionCartItems as $item) {
            // Cek apakah user sudah memiliki produk yang sama di keranjang
            $existingItem = Cart::where('user_id', $userId)
                ->where('product_id', $item->product_id)
                ->first();
                
            if ($existingItem) {
                // Update quantity jika produk sudah ada
                $existingItem->quantity += $item->quantity;
                $existingItem->save();
                
                // Hapus item dari keranjang session
                $item->delete();
            } else {
                // Pindahkan item ke keranjang user
                $item->user_id = $userId;
                $item->session_id = null;
                $item->save();
            }
        }
    }
    
    /**
     * Menghapus item dari keranjang
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function removeItem($id)
    {
        $cartItem = Cart::findOrFail($id);
        
        // Verifikasi bahwa item ini milik user yang sedang login atau session saat ini
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = Auth::check() ? null : Session::getId();
        
        if (($userId && $cartItem->user_id == $userId) || 
            ($sessionId && $cartItem->session_id == $sessionId)) {
            $cartItem->delete();
            return back()->with('success', 'Item berhasil dihapus dari keranjang');
        }
        
        return back()->with('error', 'Anda tidak memiliki akses untuk menghapus item ini');
    }
    
    /**
     * Mengupdate jumlah item di keranjang
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cartItem = Cart::findOrFail($id);
        
        // Verifikasi bahwa item ini milik user yang sedang login atau session saat ini
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = Auth::check() ? null : Session::getId();
        
        if (($userId && $cartItem->user_id == $userId) || 
            ($sessionId && $cartItem->session_id == $sessionId)) {
            
            // Cek ketersediaan stok
            $product = Product::findOrFail($cartItem->product_id);
            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Stok tidak mencukupi');
            }
            
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
            
            return back()->with('success', 'Jumlah item berhasil diupdate');
        }
        
        return back()->with('error', 'Anda tidak memiliki akses untuk mengupdate item ini');
    }

    protected function checkSellerConflict($productId)
    {
        $userId = Auth::check() ? Auth::id() : null;
        $sessionId = Auth::check() ? null : Session::getId();
        
        // Ambil seller_id produk baru
        $newProduct = Product::findOrFail($productId);
        $newSellerId = $newProduct->seller_id;
        
        // Cek apakah ada produk di keranjang
        $cartItems = Cart::with('product')
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($sessionId, function($query) use ($sessionId) {
                return $query->where('session_id', $sessionId);
            })
            ->get();
        
        // Jika keranjang kosong, tidak ada konflik
        if ($cartItems->isEmpty()) {
            return [
                'conflict' => false,
                'seller_name' => null
            ];
        }
        
        // Ambil seller_id dari produk pertama di keranjang
        $existingSellerId = $cartItems->first()->product->seller_id;
        
        // Jika seller_id berbeda, terdapat konflik
        if ($existingSellerId != $newSellerId) {
            // Dapatkan nama seller untuk pesan
            $existingSellerName = $cartItems->first()->product->seller->name ?? 'toko lain';
            
            return [
                'conflict' => true,
                'seller_name' => $existingSellerName,
                'existing_seller_id' => $existingSellerId,
                'new_seller_id' => $newSellerId
            ];
        }
        
        return [
            'conflict' => false,
            'seller_name' => null
        ];
    }
    
    public function confirmReplaceCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'action' => 'required|in:yes,no'
        ]);
        
        if ($request->action == 'yes') {
            // Arahkan ke addToCart dengan parameter replace_cart=yes
            return redirect()->route('buyer.cart.add', [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'replace_cart' => 'yes'
            ]);
        } else {
            // Kembalikan ke halaman produk tanpa menambahkan ke keranjang
            return back()->with('info', 'Produk tidak ditambahkan ke keranjang');
        }
    }
}