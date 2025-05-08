<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\ShopController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProfileController as SellerProfileController; 
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\CartController as UserCartController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ContactController; 
use App\Http\Controllers\Seller\AnalyticsController;
use App\Http\Controllers\AuthController;

Route::get('/', [HomeController::class, 'landing'])->name('welcome');

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google login
Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Routes Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/list-ongkir', function () {
    $response = Http::withHeaders([
        'key' =>  '794a5d197b9cb469ae958ed043ccf921'     
    ])->get('https://api.rajaongkir.com/starter/city'); //ganti 'province' atau 'city'
    dd($response->json());
});

Route::get('/cek-ongkir', function () {
    return view('ongkir');
});


// Route Landing
Route::get('/products', [HomeController::class, 'products'])->name('buyer.products');
Route::get('/', [HomeController::class, 'index'])->name('buyer.home');
Route::get('/categories', [App\Http\Controllers\User\CategoryController::class, 'index'])->name('buyer.categories.index');
Route::get('/category/{id}', [App\Http\Controllers\User\CategoryController::class, 'products'])->name('buyer.category.products');
Route::get('/products/category/{category}', [HomeController::class, 'category'])->name('buyer.products.category');
Route::get('/aboutus', function () {
    return view('aboutus');
})->name('aboutus');
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');  
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');  

// User Routes
Route::middleware(['auth', 'buyer'])->prefix('buyer')->name('buyer.')->group(function () {
    // Home & Product Pages
    Route::get('/product/{id}', [HomeController::class, 'product'])->name('product.detail');
    Route::get('/search', [HomeController::class, 'search'])->name('search');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
    Route::post('/contact', [HomeController::class, 'sendMessage'])->name('contact.send');
    Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
    
    // Product Show
    Route::get('/products/{product}', [UserProductController::class, 'show'])->name('products.show');

    // Keranjang
    Route::post('/cart/add', [App\Http\Controllers\User\CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [App\Http\Controllers\User\CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/{id}', [App\Http\Controllers\User\CartController::class, 'removeItem'])->name('cart.remove');
    Route::patch('/cart/{id}/quantity', [App\Http\Controllers\User\CartController::class, 'updateQuantity'])->name('cart.update-quantity');
    Route::post('/confirm-replace', [App\Http\Controllers\User\CartController::class, 'confirmReplaceCart'])->name('cart.confirm-replace');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Proses Checkout
    Route::get('/shipping', [App\Http\Controllers\User\ShippingController::class, 'index'])->name('shipping');
    Route::get('/shipping/cities', [App\Http\Controllers\User\ShippingController::class, 'getCities'])->name('shipping.cities');
    Route::post('/shipping/calculate', [App\Http\Controllers\User\ShippingController::class, 'calculateShipping'])->name('shipping.calculate');
    Route::post('/shipping/process', [App\Http\Controllers\User\ShippingController::class, 'processShipping'])->name('shipping.process');

    // Proses Pembayaran
    Route::get('/payment/{orderId}', [PaymentController::class, 'index'])->name('payment');
    
    // Callback dan Status Pembayaran
    Route::post('/payment/callback', [PaymentController::class, 'handlePaymentCallback'])->name('payment.callback')->withoutMiddleware([
         \App\Http\Middleware\VerifyCsrfToken::class,
         \App\Http\Middleware\Authenticate::class
    ]);
    Route::get('/payment/finish', [PaymentController::class, 'paymentFinish'])->name('payment.finish');
    Route::get('/payment/unfinish', [PaymentController::class, 'paymentUnfinish'])->name('payment.unfinish');
    Route::get('/payment/error', [PaymentController::class, 'paymentError'])->name('payment.error');
    Route::post('payment/update-stock', [PaymentController::class, 'updateStock'])->name('payment.update-stock');

    // Cek Order
    Route::get('/orders/history', 'App\Http\Controllers\User\OrderController@history')->name('orders.history');
    Route::get('/orders/{id}', 'App\Http\Controllers\User\OrderController@show')->name('orders.show');

    // routes/web.php
    Route::post('/cart/add-ngrok', [App\Http\Controllers\User\CartController::class, 'addToCart'])->name('cart.add.ngrok');

    // Routes Wishlist
    Route::get('/wishlist', [App\Http\Controllers\User\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/add', [App\Http\Controllers\User\WishlistController::class, 'add'])->name('add');
    Route::post('/remove', [App\Http\Controllers\User\WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/toggle', [App\Http\Controllers\User\WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/clear', [App\Http\Controllers\User\WishlistController::class, 'clear'])->name('wishlist.clear');

    Route::post('/buy-now', function(Illuminate\Http\Request $request) {
        // Validate the request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Get the product
        $product = App\Models\Product::with('images')->findOrFail($request->product_id);
        
        // Check stock availability
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }
        
        // Store buy now item in session
        Session::put('buy_now', [
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price,
            'product_name' => $product->name,
            'product_image' => $product->images->first() ? $product->images->first()->image_path : null,
            'seller_id' => $product->seller_id,
            'seller_name' => $product->seller->name ?? 'Unknown Seller',
            'timestamp' => now()->timestamp
        ]);
        
        // Redirect to Buy Now shipping page
        return redirect()->route('buyer.buy-now.shipping');
    })->name('buy-now');

    // Route for Buy Now shipping page
    Route::get('/buy-now/shipping', [App\Http\Controllers\User\ShippingController::class, 'buyNowShipping'])
        ->name('buy-now.shipping');

    // Route for processing Buy Now shipping
    Route::post('/buy-now/shipping', [App\Http\Controllers\User\ShippingController::class, 'processBuyNowShipping'])
        ->name('buy-now.process');

});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::resource('users', UserController::class);
    
    // Categories
    Route::resource('categories', AdminCategoryController::class);
    
    // Products
    Route::resource('products', AdminProductController::class);
    
    // Orders
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{order}/payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update-payment');
    Route::patch('/orders/{order}/tracking', [AdminOrderController::class, 'updateTracking'])->name('orders.update-tracking');
    Route::patch('/orders/{order}/shipping', [AdminOrderController::class, 'updateShipping'])->name('orders.update-shipping');
    Route::patch('/orders/{order}/timestamps', [AdminOrderController::class, 'updateTimestamps'])->name('orders.update-timestamps');
    Route::patch('/orders/{order}/cancel', [AdminOrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::get('/orders/{order}/print', [AdminOrderController::class, 'printInvoice'])->name('orders.print');
    Route::get('/orders/{order}/export', [AdminOrderController::class, 'exportOrder'])->name('orders.export');

    // Show image upload form (Create)
    Route::get('/products/{product}/images', [AdminProductController::class, 'showImageUploadPage'])->name('products.upload-images.show');

    // Store images (Create)
    Route::post('/products/{product}/images', [AdminProductController::class, 'uploadProductImages'])->name('products.upload-images');

    // Show product images (Read)
    Route::get('/products/{product}/images/list', [AdminProductController::class, 'showProductImages'])->name('products.images.list');

    // Route to handle the image upload
    Route::post('products/{product}/upload-images', [AdminProductController::class, 'storeUploadedImage'])->name('products.upload-images.store');

    // Update the primary image (Update)
    Route::patch('/products/{product}/images/{image}/primary', [AdminProductController::class, 'updatePrimaryImage'])->name('products.update-primary-image');

    // Delete an image (Delete)
    Route::delete('/products/{product}/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.destroy-image');
});

// Seller Routes
Route::middleware(['auth', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    
    // Shop Management
    Route::get('/shop/create', [ShopController::class, 'create'])->name('shop.create');
    Route::post('/shop', [ShopController::class, 'store'])->name('shop.store');
    Route::get('/shop/edit', [ShopController::class, 'edit'])->name('shop.edit');
    Route::put('/shop', [ShopController::class, 'update'])->name('shop.update');
    
    // Product Management
    Route::resource('products', SellerProductController::class);
    
    // Order Management
    Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [SellerOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}/status', [SellerOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('/orders/{id}/tracking', [SellerOrderController::class, 'updateTracking'])->name('orders.updateTracking');

    // Analisis
    Route::get('/analytics', [App\Http\Controllers\Seller\AnalyticsController::class, 'index'])->name('analytics');

    // Show image upload form (Create)
    Route::get('/products/{product}/images', [SellerProductController::class, 'showImageUploadPage'])->name('products.upload-images.show');

    // Store images (Create)
    Route::post('/products/{product}/images', [SellerProductController::class, 'uploadProductImages'])->name('products.upload-images');

    // Show product images (Read)
    Route::get('/products/{product}/images/list', [SellerProductController::class, 'showProductImages'])->name('products.images.list');

    // Route to handle the image upload
    Route::post('products/{product}/upload-images', [SellerProductController::class, 'storeUploadedImage'])->name('products.upload-images.store');

    // Update the primary image (Update)
    Route::patch('/products/{product}/images/{image}/primary', [SellerProductController::class, 'updatePrimaryImage'])->name('products.update-primary-image');

    // Delete an image (Delete)
    Route::delete('/products/{product}/images/{image}', [SellerProductController::class, 'destroyImage'])->name('products.destroy-image');

    Route::get('/seller/profile/edit-credentials', [SellerProfileController::class, 'editCredentials'])->name('profile.edit-credentials');
    Route::post('/reset-password', [App\Http\Controllers\Seller\ProfileController::class, 'resetPassword'])->name('profile.reset-password');
    Route::post('/change-email', [App\Http\Controllers\Seller\ProfileController::class, 'changeEmail'])->name('profile.change-email');
});