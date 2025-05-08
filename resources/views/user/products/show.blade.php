@extends('user.layouts.app')
@section('title', $product->name)

@section('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<style>
    /* Gallery Styles */
    .product-gallery {
        position: relative;
        background: #f8f9fa;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .product-image { 
        width: 100%; 
        height: 400px;
        object-fit: contain;
        transition: all 0.5s ease;
        cursor: zoom-in;
        background: #fff;
    }

    .product-image:hover { 
        transform: scale(1.03); 
    }

    .product-images .slick-slide {
        position: relative;
        overflow: hidden;
        border-radius: 0.75rem;
        margin: 0.5rem;
    }

    .slick-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        width: 40px;
        height: 40px;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        border-radius: 50%;
        display: flex !important;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .slick-arrow:hover { 
        background: rgba(0, 0, 0, 0.8);
        border-color: rgba(255, 255, 255, 0.3);
    }
    .slick-arrow:active {
        transform: translateY(-50%) scale(0.95);
    }
    .slick-prev { left: 15px; }
    .slick-next { right: 15px; }

    .slick-dots {
        bottom: -30px;
        list-style: none;
        padding: 0;
        display: flex !important;
        justify-content: center;
        gap: 8px;
    }

    .slick-dots li {
        width: 10px;
        height: 10px;
        background: #ddd;
        border-radius: 50%;
        transition: all 0.3s ease;
        cursor: pointer;
        opacity: 0.7;
    }

    .slick-dots li:hover {
        opacity: 1;
    }

    .slick-dots li.slick-active {
        background: #ec4899;
        width: 24px;
        border-radius: 5px;
        opacity: 1;
    }

    .slick-dots li button {
        display: none;
    }

    .thumbnail-container {
        position: relative;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 0.75rem;
        margin-top: 0.75rem;
    }

    .thumbnail-container::-webkit-scrollbar {
        display: none;
    }

    .thumbnail-container::before,
    .thumbnail-container::after {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 2rem;
        z-index: 5;
        pointer-events: none;
    }

    .thumbnail-container::before {
        left: 0;
        background: linear-gradient(to right, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0));
    }

    .thumbnail-container::after {
        right: 0;
        background: linear-gradient(to left, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0));
    }

    .thumbnail-item {
        position: relative;
        width: 85px;
        height: 85px;
        border: 2px solid transparent;
        cursor: pointer;
        overflow: hidden;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        flex-shrink: 0;
        margin-right: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .thumbnail-item:hover { 
        border-color: #ec4899;
        transform: translateY(-2px);
    }
    .thumbnail-item.active { 
        border-color: #ec4899;
        box-shadow: 0 0 0 2px #fce7f3, 0 4px 6px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .thumbnail-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .thumbnail-item:hover img { 
        transform: scale(1.08);
    }


    /* Zoom modal */
    .image-zoom-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.9);
        z-index: 999;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        transition: opacity 0.3s ease;
        backdrop-filter: blur(5px);
    }

    .image-zoom-modal.show {
        display: flex;
        opacity: 1;
        animation: fadeIn 0.3s ease forwards;
    }

    .zoom-content {
        position: relative;
        max-width: 90%;
        max-height: 90%;
        transform: scale(0.95);
        transition: transform 0.3s ease;
        animation: zoomIn 0.3s ease forwards;
    }

    .zoom-content img {
        max-width: 100%;
        max-height: 90vh;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    @keyframes zoomIn {
        from { transform: scale(0.95); }
        to { transform: scale(1); }
    }

    .zoom-close {
        position: absolute;
        top: -40px;
        right: 0;
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        transition: all 0.2s ease;
    }

    /* Add this to your style section */
    .wishlist-btn {
        transition: all 0.3s ease;
    }
    .wishlist-btn:hover {
        transform: scale(1.1);
    }
    .wishlist-active {
        color: #dc2626 !important;
    }
    .wishlist-btn i {
        font-size: 1.25rem;
    }

    .zoom-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    /* Perbaikan Footer Layout */
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
        padding: 0;
    }

    .main-content {
        flex: 1 0 auto;
    }

    footer {
        flex-shrink: 0;
        margin-top: auto;
        width: 100%;
        position: relative;
        bottom: 0;
        left: 0;
        right: 0;
    }

    /* Modal Mobile Styles */
    .modal-mobile {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        z-index: 100;
        box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
        transform: translateY(100%) !important;
        transition: transform 0.3s ease-out;
        max-height: 85vh;
        overflow-y: auto;
        pointer-events: none;
        visibility: hidden; /* Hidden by default */
    }

    .modal-mobile.show {
        transform: translateY(0) !important;
        pointer-events: auto;
        visibility: visible;
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 99;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        visibility: hidden; /* Hidden by default */
    }

    .modal-backdrop.show {
        opacity: 1;
        pointer-events: all;
        visibility: visible;
    }

    .modal-mobile-header {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        position: relative;
        text-align: center;
    }

    .modal-mobile-header:before {
        content: '';
        position: absolute;
        top: 0.5rem;
        left: 50%;
        transform: translateX(-50%);
        width: 4rem;
        height: 0.25rem;
        background: #e5e7eb;
        border-radius: 1rem;
    }

    .modal-mobile-body {
        padding: 1rem;
    }

    .modal-mobile-footer {
        padding: 1rem;
        border-top: 1px solid #e5e7eb;
        display: flex;
        gap: 0.5rem;
    }

    /* Enhanced Page Transition Loading Styles */
    .page-transition {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.95);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.5s ease;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .page-transition.active {
        opacity: 1;
        visibility: visible;
        pointer-events: all;
    }

    .loading-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .loading-spinner {
        position: relative;
        width: 80px;
        height: 80px;
        margin-bottom: 20px;
    }

    .loading-spinner:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 4px solid transparent;
        border-top-color: #ec4899;
        border-bottom-color: #ec4899;
        animation: spin 1.5s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
    }

    .loading-spinner:after {
        content: '';
        position: absolute;
        top: 15%;
        left: 15%;
        width: 70%;
        height: 70%;
        border-radius: 50%;
        border: 4px solid transparent;
        border-left-color: #ec4899;
        border-right-color: #ec4899;
        animation: spin 1.2s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite reverse;
    }

    .loading-text {
        color: #db2777;
        font-size: 16px;
        font-weight: 500;
        letter-spacing: 0.5px;
        text-align: center;
    }

    .loading-progress {
        width: 100px;
        height: 4px;
        background: #f3e8f0;
        border-radius: 4px;
        margin-top: 10px;
        overflow: hidden;
        position: relative;
    }

    .loading-progress-bar {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #ec4899, #db2777);
        border-radius: 4px;
        transition: width 0.3s ease;
        animation: progress 2s ease-in-out infinite;
    }

    @keyframes progress {
        0% { width: 0%; }
        50% { width: 70%; }
        70% { width: 85%; }
        100% { width: 100%; }
    }

    /* Tambahkan ini ke bagian CSS di <style> */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease;
    }

    .loading-overlay.show {
        opacity: 1;
        visibility: visible;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive modal styles */
    @media (min-width: 769px) {
        .modal-mobile, .modal-backdrop {
            display: none !important;
        }
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .product-image { height: 280px; }
        .slick-arrow { 
            width: 35px; 
            height: 35px; 
        }
        .thumbnail-item {
            width: 65px;
            height: 65px;
        }
        .slick-dots { bottom: -20px; }
        
        /* Mobile Fixed Footer */
        body {
            padding-bottom: 70px !important; /* Space for fixed buttons */
        }
        
        .mobile-buttons {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #e5e7eb;
            padding: 1rem;
            gap: 1rem;
            display: flex !important;
            z-index: 50;
            box-shadow: 0 -4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .desktop-only { display: none !important; }
        
        /* Ensure main content fills space */
        .main-content {
            flex: 1;
            margin-bottom: 0;
        }

        .zoom-content img {
            max-height: 80vh;
        }
        
        /* Remove bottom space */
        .container {
            padding-bottom: 0 !important;
        }
        
        /* Fixed bottom */
        .related-products {
            margin-bottom: 0 !important;
        }
        
        /* Ensure footer sits flush at bottom with no extra space */
        footer {
            margin-bottom: 0;
            padding-bottom: 0;
        }
    }

    .product-image.loading,
    .thumbnail-item img.loading {
        background: linear-gradient(90deg, #f0f0f0 0%, #f8f8f8 50%, #f0f0f0 100%);
        background-size: 200% 100%;
        animation: loadingPulse 1.5s infinite;
    }

    @keyframes loadingPulse {
        0% { background-position: 0% 0; }
        100% { background-position: -200% 0; }
    }

    .quantity-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f3f4f6;
        transition: 0.2s;
    }

    .quantity-btn:hover { background: #e5e7eb; }

    .tab-btn {
        padding: 0.75rem 1rem;
        color: #4b5563;
        font-weight: 500;
        border-bottom: 2px solid transparent;
    }

    .tab-btn.active { 
        color: #db2777;
        border-bottom-color: #db2777;
    }

    /* Hide number input arrows */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    /* Mobile Slide Up Animation */
    @keyframes slideUp {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .modal-mobile.animate-slide-up {
        animation: slideUp 0.3s ease-out forwards;
    }

    .modal-backdrop.animate-fade-in {
        animation: fadeIn 0.3s ease forwards;
    }

    /* Prevent body scroll when modal is open */
    body.modal-open {
        overflow: hidden !important;
        position: fixed;
        width: 100%;
        height: 100%;
    }

    /* Modal untuk notifikasi */
    .notification-toast {
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        z-index: 1000;
        max-width: 80vw;
    }

    /* Animasi notifikasi */
    @keyframes fadeInSlideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .notification-toast {
        animation: fadeInSlideDown 0.3s ease-out forwards;
    }

    /* Pull to refresh indicator (optional untuk UX mobile) */
    .pull-to-refresh {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ec4899;
        transform: translateY(-100%);
        transition: transform 0.3s;
    }

    .pull-to-refresh.visible {
        transform: translateY(0);
    }
</style>
@endsection

@section('content')
<!-- Mobile First Design -->
<div class="main-content">
    <!-- Page Transition Loading Effect -->
    <div class="page-transition" id="pageTransition">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text" id="loadingText">Loading page...</div>
            <div class="loading-progress">
                <div class="loading-progress-bar"></div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay - Retained for backward compatibility -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <p class="loading-text">Sedang memproses...</p>
    </div>

    <div class="container mx-auto px-4 py-4">
        <!-- Product Gallery yang Ditingkatkan -->
        <div class="mb-6 product-gallery">
            @if($product->images && $product->images->count() > 0)
                <div class="product-images relative">
                    @foreach($product->images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                alt="{{ $product->name }}" 
                                class="product-image w-full rounded-lg"
                                data-zoom="{{ asset('storage/' . $image->image_path) }}"
                                onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                            
                            <!-- Tambahkan overlay hover -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center">
                                <span class="text-white opacity-0 hover:opacity-100 transition-opacity duration-300">
                                    <i class="fas fa-search-plus text-2xl"></i>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Thumbnails yang Ditingkatkan -->
                <div class="thumbnail-container flex mt-4 pb-2">
                    @foreach($product->images as $index => $image)
                        <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                alt="Thumbnail" 
                                onerror="this.onerror=null; this.src='{{ asset('images/no-image.jpg') }}';">
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300"></div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="relative rounded-lg overflow-hidden">
                    <img src="{{ asset('images/no-image.jpg') }}" 
                        alt="{{ $product->name }}" 
                        class="product-image rounded-lg">
                    <div class="absolute inset-0 flex items-center justify-center bg-gray-100 bg-opacity-50">
                        <span class="text-gray-500">
                            <i class="fas fa-image text-4xl mb-2"></i>
                            <p class="text-sm">Gambar tidak tersedia</p>
                        </span>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Zoom Modal -->
        <div class="image-zoom-modal" id="imageZoomModal">
            <div class="zoom-content">
                <button class="zoom-close">&times;</button>
                <img id="zoomedImage" src="" alt="">
            </div>
        </div>
        
        <!-- Product Info -->
        <div class="bg-white rounded-xl p-4 mb-4">
            <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>
            
            <!-- Rating & Stock -->
            <div class="flex items-center gap-4 mb-4">
                <span class="text-sm {{ ($product->stock > 0) ? 'text-green-500' : 'text-red-500' }}">
                    {{ ($product->stock > 0) ? 'In Stock (' . $product->stock . ' available)' : 'Out of Stock' }}
                </span>
            </div>
            
            <!-- Price -->
            <div class="mb-4">
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl font-bold text-pink-600">
                            Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                        </span>
                        
                        @if($product->original_price && $product->original_price > $product->price)
                            <span class="line-through text-gray-400">
                                Rp {{ number_format($product->original_price, 0, ',', '.') }}
                            </span>
                            <span class="bg-pink-100 text-pink-800 text-xs px-2 py-1 rounded">
                                {{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}% OFF
                            </span>
                        @endif
                    </div>

                    <!-- Wishlist Button positioned on the right -->
                    @php
                        $isInWishlist = auth()->user() && auth()->user()->hasInWishlist($product->id);
                    @endphp
                    <button type="button" 
                            id="wishlistButton" 
                            onclick="toggleWishlist(event, {{ $product->id }})" 
                            class="wishlist-btn bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-md hover:shadow-lg z-10 {{ $isInWishlist ? 'wishlist-active' : '' }}">
                        <i class="fas fa-heart text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Description -->
            <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 150) }}</p>
            
            <!-- Quantity Selector -->
            <form id="addToCartForm" action="{{ request()->getHost() == 'localhost' ? route('buyer.cart.add') : route('buyer.cart.add.ngrok') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                
                <div class="flex items-center gap-4 mb-4">
                    <label class="font-medium">Quantity:</label>
                    <div class="flex items-center">
                        <button type="button" class="quantity-btn rounded-l-md" onclick="changeQuantity(-1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" name="quantity" value="1" min="1" 
                               max="{{ $product->stock ?? 10 }}" 
                               class="w-16 text-center py-2 border-y border-gray-200">
                        <button type="button" class="quantity-btn rounded-r-md" onclick="changeQuantity(1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Desktop Buttons -->
                <div class="desktop-only grid grid-cols-2 gap-4">
                    <button type="submit" class="bg-pink-600 text-white py-3 rounded-md flex items-center justify-center">
                        <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                    </button>
                    <button type="button" id="buyNowBtn" class="border-2 border-pink-600 text-pink-600 py-3 rounded-md flex items-center justify-center">
                        <i class="fas fa-bolt mr-2"></i> Buy Now
                    </button>
                </div>
            </form>
            <form id="buyNowForm" action="{{ route('buyer.buy-now') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" id="buyNowQuantity" value="1">
            </form>
        </div>
        
        <!-- Tabs -->
        <div class="bg-white rounded-xl mb-6">
            <div class="flex overflow-x-auto border-b">
                <button class="tab-btn active" data-target="description">Description</button>
                <button class="tab-btn" data-target="specifications">Specifications</button>
                <button class="tab-btn" data-target="reviews">Reviews</button>
            </div>
            
            <div class="p-4">
                <div id="description" class="tab-content">
                    <h3 class="font-bold mb-2">Product Description</h3>
                    <p class="text-gray-600">{{ $product->description }}</p>
                </div>
                
                <div id="specifications" class="tab-content hidden">
                    <h3 class="font-bold mb-2">Specifications</h3>
                    <table class="w-full">
                        <tr class="border-b">
                            <td class="py-2 font-medium">Brand</td>
                            <td class="py-2">{{ $product->brand ?? 'N/A' }}</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium">Weight</td>
                            <td class="py-2">{{ $product->weight ?? '0' }} g</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium">Warranty</td>
                            <td class="py-2">{{ $product->warranty ?? '12 months' }}</td>
                        </tr>
                    </table>
                </div>
                
                <div id="reviews" class="tab-content hidden">
                    <h3 class="font-bold mb-2">Customer Reviews</h3>
                    @if($product->reviews && $product->reviews->count() > 0)
                        @foreach($product->reviews as $review)
                            <div class="border-b py-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="font-medium">{{ $review->user->name ?? 'Anonymous' }}</span>
                                    <div class="text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= ($review->rating ?? 0) ? '' : '-o' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-600">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-500">No reviews yet</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <div class="related-products mb-6">
            <h2 class="text-xl font-bold mb-4">Related Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @if(isset($relatedProducts) && $relatedProducts->count() > 0)
                    @foreach($relatedProducts as $relatedProduct)
                        <a href="{{ route('buyer.products.show', $relatedProduct->id) }}" class="bg-white rounded-lg overflow-hidden">
                            <img src="{{ $relatedProduct->images->first() ? asset('storage/' . $relatedProduct->images->first()->image_path) : asset('images/no-image.jpg') }}" 
                                 alt="{{ $relatedProduct->name }}" 
                                 class="w-full h-32 object-cover">
                            <div class="p-2">
                                <h3 class="font-medium text-sm truncate">{{ $relatedProduct->name }}</h3>
                                <p class="text-pink-600 font-semibold">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</p>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
        
        <!-- Div kosong untuk memastikan footer selalu ada di bawah pada halaman pendek -->
        <div class="footer-spacer"></div>
    </div>
</div>

<!-- Modal Backdrop utama -->
<div id="mobileReplaceCartBackdrop" class="modal-backdrop {{ session('seller_conflict') ? 'show' : '' }}"></div>

<!-- Mobile Modal Konfirmasi Ganti Keranjang -->
<div id="mobileReplaceCartModal" class="modal-mobile {{ session('seller_conflict') ? 'show' : '' }}">
    <div class="modal-mobile-header">
        <h3 class="font-bold">Konfirmasi Ganti Produk</h3>
    </div>
    <div class="modal-mobile-body">
        <p class="mb-4 text-gray-700">
            Di keranjang Anda terdapat produk dari Toko {{ session('seller_name') ?? 'toko lain' }} dan Anda tidak bisa memilih produk dari toko yang berbeda.
        </p>
        
        <p class="mb-4 text-gray-700">
            Apakah Anda ingin mengganti ke produk tersebut lalu menghapus produk sebelumnya?
        </p>
    </div>
    <div class="modal-mobile-footer">
        <form action="{{ route('buyer.cart.confirm-replace') }}" method="POST" class="w-full">
            @csrf
            <input type="hidden" name="product_id" value="{{ session('product_id') }}">
            <input type="hidden" name="quantity" value="{{ session('quantity') }}">
            
            <div class="grid grid-cols-2 gap-3 w-full">
                <button type="submit" name="action" value="no" class="col-span-1 px-4 py-3 border border-gray-300 rounded-md text-gray-600 font-medium">
                    Tidak
                </button>
                
                <button type="submit" name="action" value="yes" class="col-span-1 px-4 py-3 bg-pink-600 text-white rounded-md font-medium">
                    Ya, Ganti
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Ganti Keranjang (Desktop) -->
<div id="replaceCartModal" class="fixed inset-0 z-50 flex items-center justify-center {{ session('seller_conflict') ? 'flex' : 'hidden' }}">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto z-10">
        <h3 class="text-lg font-bold mb-4">Konfirmasi Ganti Produk di Keranjang</h3>
        
        <p class="mb-4 text-gray-700">
            Di keranjang Anda terdapat produk dari Toko {{ session('seller_name') ?? 'toko lain' }} dan Anda tidak bisa memilih produk dari toko yang berbeda.
        </p>
        
        <p class="mb-4 text-gray-700">
            Apakah Anda ingin mengganti ke produk tersebut lalu menghapus produk sebelumnya?
        </p>
        
        <div class="flex justify-end gap-3 mt-4">
            <form action="{{ route('buyer.cart.confirm-replace') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ session('product_id') }}">
                <input type="hidden" name="quantity" value="{{ session('quantity') }}">
                
                <button type="submit" name="action" value="no" class="px-4 py-2 border border-gray-300 rounded text-gray-600">
                    Tidak, saya ingin melihat-lihat saja
                </button>
                
                <button type="submit" name="action" value="yes" class="px-4 py-2 bg-pink-600 text-white rounded ml-2">
                    Ya, saya ingin menggantinya
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Mobile Fixed Actions -->
<div class="mobile-buttons">
    <button type="submit" form="addToCartForm" class="flex-1 bg-pink-600 text-white py-3 rounded-md flex items-center justify-center">
        <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
    </button>
    <button type="button" id="mobileBuyNowBtn" class="flex-1 border-2 border-pink-600 text-pink-600 py-3 rounded-md flex items-center justify-center">
        <i class="fas fa-bolt mr-2"></i> Buy Now
    </button>
</div>

<!-- Optional: Pull to refresh indicator -->
<div class="pull-to-refresh">
    <i class="fas fa-sync-alt fa-spin mr-2"></i>
    Menyegarkan...
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script>
    $(document).ready(function(){
        // Show initial loading when DOM is ready
        showPageLoading('Loading page...');


        var loadingTimeout = setTimeout(function() {
            hidePageLoading();
            console.log('Fallback: Layar loading disembunyikan oleh timeout');
        }, 2000);

        var originalHideFunction = hidePageLoading;
        hidePageLoading = function() {
            clearTimeout(loadingTimeout);
            originalHideFunction.apply(this, arguments);
        };
        
        // Hide loading effect on page load
        $(window).on('load', function() {
            try {
                hidePageLoading();
                console.log('Layar loading disembunyikan oleh event window.load');
            } catch(e) {
                console.error('Error di event load:', e);
                $('#pageTransition').removeClass('active');
                $('body').removeClass('modal-open');
            }
        });

        // Wishlist Toggle
        // Wishlist Toggle with improved debugging
        function toggleWishlist(event, productId) {
            console.log('Toggle wishlist clicked:', productId);
            
            // Prevent any default behavior
            event.preventDefault();
            event.stopPropagation();
            
            // Check if user is logged in
            @if(!auth()->check())
                console.log('User not logged in, showing login dialog');
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Login Diperlukan',
                    text: 'Silakan login terlebih dahulu untuk menambahkan produk ke wishlist',
                    showCancelButton: true,
                    confirmButtonColor: '#db2777', // Match your pink-600 color
                    cancelButtonColor: '#d1d5db',
                    confirmButtonText: 'Login Sekarang',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        showPageLoading('Redirecting to login...');
                        window.location.href = '{{ route("login") }}';
                    }
                });
                return;
            @else
                console.log('User is logged in, proceeding with wishlist toggle');
                
                // Get the button
                const wishlistBtn = document.getElementById('wishlistButton');
                
                // Show loading indicator
                wishlistBtn.innerHTML = '<i class="fas fa-spinner fa-spin text-xl"></i>';
                wishlistBtn.disabled = true;
                
                // Add loading class
                wishlistBtn.classList.add('pointer-events-none', 'opacity-75');
                
                fetch('{{ route("buyer.wishlist.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => {
                    console.log('Wishlist toggle response:', response);
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    return response.json();
                })
                .then(data => {
                    console.log('Wishlist toggle success data:', data);
                    
                    // Reset button state
                    wishlistBtn.innerHTML = '<i class="fas fa-heart text-xl"></i>';
                    wishlistBtn.disabled = false;
                    wishlistBtn.classList.remove('pointer-events-none', 'opacity-75');
                    
                    if(data.status === 'success') {
                        if(data.action === 'added') {
                            wishlistBtn.classList.add('wishlist-active');
                        } else {
                            wishlistBtn.classList.remove('wishlist-active');
                        }
                        
                        // Show success message
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                })
                .catch(error => {
                    console.error('Wishlist toggle error:', error);
                    
                    // Reset button
                    wishlistBtn.innerHTML = '<i class="fas fa-heart text-xl"></i>';
                    wishlistBtn.disabled = false;
                    wishlistBtn.classList.remove('pointer-events-none', 'opacity-75');
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end', 
                        icon: 'error',
                        title: 'Error: ' + error.message,
                        showConfirmButton: false,
                        timer: 2000
                    });
                });
            @endif
        }

        // Add this to document ready to ensure button is clickable
        $(document).ready(function() {
            // Make sure wishlist button is clickable by adding it to document ready
            $('#wishlistButton').off('click').on('click', function(e) {
                const productId = $(this).data('product-id');
                toggleWishlist(e, {{ $product->id }});
            });
        });
        
        // Initialize AOS
        AOS.init({ once: true });
        
        // Product image slider with improved features
        $('.product-images').slick({
            dots: true,
            arrows: true,
            fade: true,
            autoplay: false,
            speed: 500,
            cssEase: 'ease-in-out',
            mobileFirst: true,
            prevArrow: '<button class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="fas fa-chevron-right"></i></button>',
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        arrows: true
                    }
                }
            ]
        });
        
        // Thumbnail navigation
        $('.thumbnail-item').click(function() {
            $('.thumbnail-item').removeClass('active');
            $(this).addClass('active');
            $('.product-images').slick('slickGoTo', $(this).index());
        });
        
        // Sync thumbnails with slider
        $('.product-images').on('beforeChange', function(event, slick, currentSlide, nextSlide){
            $('.thumbnail-item').removeClass('active');
            $('.thumbnail-item').eq(nextSlide).addClass('active');
            
            // Scroll thumbnail into view
            const thumbnailContainer = $('.thumbnail-container');
            const activeThumbnail = $('.thumbnail-item').eq(nextSlide);
            const containerWidth = thumbnailContainer.width();
            const scrollLeft = activeThumbnail.offset().left - thumbnailContainer.offset().left + thumbnailContainer.scrollLeft();
            
            thumbnailContainer.animate({
                scrollLeft: scrollLeft - (containerWidth / 2) + (activeThumbnail.width() / 2)
            }, 300);
        });
        
        // Image zoom functionality
        $('.product-image').click(function() {
            const zoomSrc = $(this).data('zoom');
            
            // Tambahkan efek loading saat menunggu gambar zoom dimuat
            $('#zoomedImage').addClass('loading');
            
            // Atur src gambar
            $('#zoomedImage').attr('src', zoomSrc);
            
            // Tambahkan class untuk menampilkan modal
            $('#imageZoomModal').addClass('show');
            
            // Tambahkan listener untuk menghapus efek loading saat gambar dimuat
            $('#zoomedImage').on('load', function() {
                $(this).removeClass('loading');
            });
            
            // Cegah scrolling pada body
            $('body').addClass('modal-open');
        });
        
        $('.zoom-close').click(function() {
            $('#imageZoomModal').removeClass('show');
        });
        
        // Close modal on outside click
        $('#imageZoomModal').click(function(e) {
            if (e.target === this) {
                $(this).removeClass('show');
            }
        });
        
        // Close modal on Escape key
        $(document).keydown(function(e) {
            if ($('.product-images').length) {
                if (e.key === "ArrowLeft") {
                    $('.product-images').slick('slickPrev');
                } else if (e.key === "ArrowRight") {
                    $('.product-images').slick('slickNext');
                }
            }
        });
        
        // Tab navigation
        $('.tab-btn').click(function() {
            $('.tab-btn').removeClass('active');
            $(this).addClass('active');
            
            $('.tab-content').addClass('hidden');
            $('#' + $(this).data('target')).removeClass('hidden');
        });

        // Tambahkan timeout pengaman untuk paksa menghilangkan layar loading
        setTimeout(function() {
            $('#pageTransition').removeClass('active').css('opacity', '');
            $('body').removeClass('modal-open');
            console.log('Penghapusan layar loading darurat dieksekusi');
        }, 3000);
        
        function setupImageLoading() {
            const productImages = document.querySelectorAll('.product-image, .thumbnail-item img');
            
            productImages.forEach(img => {
                // Tambahkan class loading
                img.classList.add('loading');
                
                // Hapus class loading saat gambar selesai dimuat
                img.onload = function() {
                    this.classList.remove('loading');
                    // Tambahkan fade in effect
                    this.animate([
                        { opacity: 0 },
                        { opacity: 1 }
                    ], {
                        duration: 300,
                        fill: 'forwards'
                    });
                };
            });
        }


        // Inisialisasi loading saat DOM sudah siap
        setupImageLoading();

        // Pastikan footer selalu mengisi penuh halaman
        function adjustFooter() {
            const windowHeight = window.innerHeight;
            const bodyHeight = document.body.offsetHeight;
            const footer = document.querySelector('footer');
            
            // Jika bodyHeight kurang dari windowHeight, sesuaikan footer
            if (bodyHeight < windowHeight && footer) {
                const spacer = document.querySelector('.footer-spacer');
                if (spacer) {
                    spacer.style.height = (windowHeight - bodyHeight) + 'px';
                }
            }
        }
        
        // Panggil fungsi saat halaman dimuat dan saat resize
        adjustFooter();
        $(window).on('resize', adjustFooter);
        
        // Perbaikan untuk mencegah modal muncul saat scroll
        let lastScrollTop = 0;
        let isScrolling;
        
        $(window).on('scroll', function() {
            const st = $(window).scrollTop();
            
            // Clear timeout sepanjang scroll
            clearTimeout(isScrolling);
            
            // Jika scroll ke bawah, pastikan modal tidak muncul
            if (st > lastScrollTop) {
                if (!$('#mobileReplaceCartModal').hasClass('show')) {
                    $('#mobileReplaceCartModal').css({
                        'transform': 'translateY(100%) !important',
                        'visibility': 'hidden'
                    });
                }
            }
            
            lastScrollTop = st;
            
            // Set timeout baru untuk mendeteksi kapan scroll berhenti
            isScrolling = setTimeout(function() {
                // Pastikan modal tetap tersembunyi setelah scrolling
                if (!$('#mobileReplaceCartModal').hasClass('show') && 
                    !$('#dynamicMobileReplaceCartModal').hasClass('show')) {
                    $('#mobileReplaceCartModal').css({
                        'transform': 'translateY(100%) !important',
                        'visibility': 'hidden'
                    });
                }
            }, 100);
        });
        
        // Modal handling untuk konflik seller
        @if(session('seller_conflict'))
            // Tambahkan delay untuk mencegah muncul karena scroll
            setTimeout(function() {
                // Deteksi apakah perangkat mobile
                if (window.innerWidth <= 768) {
                    showMobileModal();
                } else {
                    $('#replaceCartModal').removeClass('hidden').addClass('flex');
                }
            }, 300);
        @endif
        
        // Menutup modal dengan tombol close atau klik di luar
        $('.modal-close, #replaceCartModal').click(function(e) {
            if (e.target === this) {
                $('#replaceCartModal').removeClass('flex').addClass('hidden');
            }
        });
        
        // Modal mobile backdrop click
        $('#mobileReplaceCartBackdrop').click(function() {
            closeMobileModal();
        });
        
        // Enhanced Page Loading Functions
        function showPageLoading(message = 'Loading page...') {
            // Update loading text
            $('#pageTransition .loading-text').text(message);
            
            // Show loading overlay with fade-in effect
            $('#pageTransition').addClass('active');
            
            // Prevent scrolling while loading
            $('body').addClass('modal-open');
        }
        
        
        function hidePageLoading() {
            // Start fade-out
            $('#pageTransition').css('opacity', '0');
            
            // Complete transition and hide
            setTimeout(() => {
                $('#pageTransition').removeClass('active').css('opacity', '');
                
                // Enable scrolling again if no other modals are open
                if (!$('#mobileReplaceCartModal').hasClass('show') && 
                    !$('#dynamicMobileReplaceCartModal').hasClass('show')) {
                    $('body').removeClass('modal-open');
                }
            }, 500);
        }
        
        // Show loading when clicking on navigation links (not anchors or JS links)
        $(document).on('click', 'a[href]:not([href^="#"]):not([href^="javascript"]):not([href^="mailto"]):not([target="_blank"])', function(e) {
            const href = $(this).attr('href');
            if (href) {
                e.preventDefault();
                showPageLoading('Navigating to page...');
                
                // Delayed navigation for better UX
                setTimeout(() => {
                    window.location.href = href;
                }, 300);
            }
        });
        
        // Add page transitions for mobile bottom navigation
        $('.mobile-navigation a, .related-products a').on('click', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');
            if (href) {
                showPageLoading('Loading product...');
                setTimeout(() => {
                    window.location.href = href;
                }, 300);
            }
        });
        
        // Fungsi untuk menampilkan modal mobile dengan lebih aman
        function showMobileModal() {
            // Simpan posisi scroll terlebih dahulu
            const scrollPosition = window.pageYOffset;
            
            // Tambahkan backdrop jika belum ada
            if ($('#mobileReplaceCartBackdrop').length === 0) {
                $('body').append('<div id="mobileReplaceCartBackdrop" class="modal-backdrop"></div>');
            }
            
            // Tambahkan class untuk mencegah scroll
            $('body').addClass('modal-open');
            $('body').css('top', `-${scrollPosition}px`);
            
            // Pastikan visibility diatur ke visible sebelum transformasi
            $('#mobileReplaceCartModal').css('visibility', 'visible');
            
            // Tampilkan backdrop dulu
            $('#mobileReplaceCartBackdrop').addClass('show');
            
            // Kemudian tampilkan modal dengan sedikit delay
            setTimeout(function() {
                $('#mobileReplaceCartModal').css('transform', 'translateY(0) !important').addClass('show');
            }, 50);
        }
        
        // Fungsi untuk menutup modal mobile dengan aman
        function closeMobileModal() {
            // Kembalikan modal ke posisi tersembunyi
            $('#mobileReplaceCartModal').css('transform', 'translateY(100%) !important').removeClass('show');
            
            // Kemudian sembunyikan backdrop
            setTimeout(function() {
                $('#mobileReplaceCartBackdrop').removeClass('show');
                $('#mobileReplaceCartModal').css('visibility', 'hidden');
                
                // Kembalikan scroll position
                if ($('body').hasClass('modal-open')) {
                    const scrollY = parseInt($('body').css('top') || '0') * -1;
                    $('body').removeClass('modal-open');
                    $('body').css('top', '');
                    window.scrollTo(0, scrollY);
                }
            }, 300);
        }
        
        // Modifikasi AJAX untuk Add to Cart pada fungsi submit dengan loading animasi
        $('#addToCartForm').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
            
            // Gunakan loading effect
            showPageLoading('Adding to cart...');
            
            // Tambahkan debugging untuk melihat URL yang digunakan
            console.log('Submit URL:', $(this).attr('action'));
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Success response:', response);
                    $('.cart-counter').text(response.cartCount);
                    
                    setTimeout(function() {
                        hidePageLoading();
                        showNotification(response.message, 'success');
                    }, 800);
                },
                error: function(xhr, status, error) {
                    console.log('Error status:', status);
                    console.log('Error message:', error);
                    console.log('Response Text:', xhr.responseText);
                    
                    hidePageLoading();
                    
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (xhr.status === 409 && response.conflict) {
                            showSellerConflictModal(response.message, response.product_id, $('#addToCartForm input[name="quantity"]').val());
                        } else {
                            showNotification(response.message || 'Terjadi kesalahan', 'error');
                        }
                    } catch(e) {
                        showNotification('Terjadi kesalahan sistem', 'error');
                    }
                }
            });
        });

        // Tambahkan ini ke bagian script Anda
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Buy Now button click handler (desktop)
            $('#buyNowBtn').click(function(e) {
                e.preventDefault();
                processBuyNow();
            });
            
            // Buy Now button click handler (mobile)
            $('#mobileBuyNowBtn').click(function(e) {
                e.preventDefault();
                processBuyNow();
            });
            
            // Function to handle Buy Now process
            function processBuyNow() {
                // Update quantity in the Buy Now form to match the quantity in the Add to Cart form
                $('#buyNowQuantity').val($('input[name="quantity"]').val());
                
                // Show loading overlay
                showPageLoading('Processing your order...');
                
                // Submit the Buy Now form
                $('#buyNowForm').submit();
            }
            
            // Update the Buy Now quantity when the main quantity changes
            $('input[name="quantity"]').on('change', function() {
                $('#buyNowQuantity').val($(this).val());
            });
        });

        // Fungsi untuk me-refresh konten halaman tanpa refresh browser
        function silentRefresh() {
            showPageLoading('Updating page...');
            
            // Ambil URL halaman saat ini
            const currentUrl = window.location.href;
            
            // Gunakan AJAX untuk mengambil konten halaman baru
            $.ajax({
                url: currentUrl,
                type: 'GET',
                success: function(response) {
                    // Parse HTML response
                    const newContent = $(response);
                    
                    // Ganti konten .main-content dengan yang baru
                    $('.main-content').html(newContent.find('.main-content').html());
                    
                    // Update cart counter if present
                    if (newContent.find('.cart-counter').length) {
                        $('.cart-counter').text(newContent.find('.cart-counter').text());
                    }
                    
                    // Reinitialize components
                    reinitializeComponents();
                    
                    // Hide loading
                    hidePageLoading();
                    
                    // Scroll to top
                    window.scrollTo(0, 0);
                },
                error: function() {
                    // Hide loading
                    hidePageLoading();
                    
                    // Show error notification
                    showNotification('Failed to update page, trying again...', 'error');
                    
                    // Fallback to normal refresh
                    setTimeout(function() {
                        showPageLoading('Reloading page...');
                        window.location.reload();
                    }, 1000);
                }
            });
        }

        // Fungsi untuk menampilkan modal konflik seller secara dinamis dan lebih aman
        function showSellerConflictModal(message, productId, quantity) {
            // Hapus modal yang mungkin sudah ada sebelumnya
            $('#dynamicReplaceCartModal, #dynamicMobileReplaceCartModal, #dynamicMobileReplaceCartBackdrop').remove();
            
            // Deteksi apakah perangkat mobile
            if (window.innerWidth <= 768) {
                // Buat modal mobile HTML
                const mobileBackdropHtml = `<div id="dynamicMobileReplaceCartBackdrop" class="modal-backdrop"></div>`;
                const mobileModalHtml = `
                    <div id="dynamicMobileReplaceCartModal" class="modal-mobile" data-product-id="${productId}" data-quantity="${quantity}">
                        <div class="modal-mobile-header">
                            <h3 class="font-bold">Konfirmasi Ganti Produk</h3>
                        </div>
                        <div class="modal-mobile-body">
                            <p class="mb-4 text-gray-700">${message}</p>
                            
                            <p class="mb-4 text-gray-700">
                                Apakah Anda ingin mengganti ke produk tersebut lalu menghapus produk sebelumnya?
                            </p>
                        </div>
                        <div class="modal-mobile-footer">
                            <div class="grid grid-cols-2 gap-3 w-full">
                                <button type="button" id="mobileRejectReplace" class="col-span-1 px-4 py-3 border border-gray-300 rounded-md text-gray-600 font-medium">
                                    Tidak
                                </button>
                                
                                <button type="button" id="mobileAcceptReplace" class="col-span-1 px-4 py-3 bg-pink-600 text-white rounded-md font-medium">
                                    Ya, Ganti
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Simpan posisi scroll
                const scrollPosition = window.pageYOffset;
                
                // Tambahkan modal mobile ke body
                $('body').append(mobileBackdropHtml + mobileModalHtml);
                
                // Tambahkan class untuk mencegah scroll
                $('body').addClass('modal-open');
                $('body').css('top', `-${scrollPosition}px`);
                
                // Pastikan visibility diatur ke visible sebelum transformasi
                $('#dynamicMobileReplaceCartModal').css('visibility', 'visible');
                
                // Animasi tampilkan modal
                setTimeout(function() {
                    $('#dynamicMobileReplaceCartBackdrop').addClass('show');
                    setTimeout(function() {
                        $('#dynamicMobileReplaceCartModal').css('transform', 'translateY(0) !important').addClass('show');
                    }, 50);
                }, 10);
                
                // Event handler untuk tombol "Tidak"
                $('#mobileRejectReplace').click(function() {
                    closeDynamicMobileModal();
                });
                
                // Event handler untuk tombol "Ya"
                $('#mobileAcceptReplace').click(function() {
                    // AJAX request untuk mengganti keranjang
                    replacementRequest(productId, quantity);
                });
                
                // Event handler untuk backdrop
                $('#dynamicMobileReplaceCartBackdrop').click(function() {
                    closeDynamicMobileModal();
                });
                
                // Tambahkan handler swipe untuk modal dinamis
                bindSwipeHandlers('#dynamicMobileReplaceCartModal');
                
            } else {
                // Untuk desktop, gunakan modal yang sudah ada sebelumnya
                const modalHtml = `
                    <div id="dynamicReplaceCartModal" class="fixed inset-0 z-50 flex items-center justify-center" data-product-id="${productId}" data-quantity="${quantity}">
                        <div class="absolute inset-0 bg-black opacity-50"></div>
                        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-auto z-10">
                            <h3 class="text-lg font-bold mb-4">Konfirmasi Ganti Produk di Keranjang</h3>
                            
                            <p class="mb-4 text-gray-700">${message}</p>
                            
                            <p class="mb-4 text-gray-700">
                                Apakah Anda ingin mengganti ke produk tersebut lalu menghapus produk sebelumnya?
                            </p>
                            
                            <div class="flex justify-end gap-3 mt-4">
                                <button type="button" id="btnRejectReplace" class="px-4 py-2 border border-gray-300 rounded text-gray-600">
                                    Tidak, saya ingin melihat-lihat saja
                                </button>
                                
                                <button type="button" id="btnAcceptReplace" class="px-4 py-2 bg-pink-600 text-white rounded ml-2">
                                    Ya, saya ingin menggantinya
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                // Tambahkan modal ke body
                $('body').append(modalHtml);
                
                // Event handler untuk tombol "Tidak"
                $('#btnRejectReplace').click(function() {
                    $('#dynamicReplaceCartModal').remove();
                });
                
                // Event handler untuk tombol "Ya"
                $('#btnAcceptReplace').click(function() {
                    // AJAX request untuk mengganti keranjang
                    replacementRequest(productId, quantity);
                });
                
                // Event handler untuk klik di luar modal
                $('#dynamicReplaceCartModal').click(function(e) {
                    if (e.target === this) {
                        $(this).remove();
                    }
                });
            }
        }
        
        // Fungsi untuk bind handler swipe pada elemen modal
        function bindSwipeHandlers(modalSelector) {
            let touchStartY = 0;
            let touchEndY = 0;
            
            $(modalSelector).on('touchstart', function(e) {
                touchStartY = e.originalEvent.touches[0].clientY;
            });
            
            $(modalSelector).on('touchmove', function(e) {
                // Hanya proses touch jika di bagian atas modal dan scroll ke bawah
                if ($(this).scrollTop() <= 0) {
                    touchEndY = e.originalEvent.touches[0].clientY;
                    
                    // Jika swipe ke bawah (touchEndY > touchStartY), tambahkan transformasi
                    if (touchEndY > touchStartY) {
                        // Hanya transformasi sebagian untuk efek pegas
                        const translateY = Math.min(100, (touchEndY - touchStartY) / 2);
                        $(this).css('transform', `translateY(${translateY}px)`);
                        e.preventDefault(); // Cegah scroll body
                    }
                }
            });
            
            $(modalSelector).on('touchend', function(e) {
                // Jika swipe ke bawah cukup jauh, tutup modal
                if ($(this).scrollTop() <= 0 && touchEndY - touchStartY > 100) {
                    if ($(this).attr('id') === 'dynamicMobileReplaceCartModal') {
                        closeDynamicMobileModal();
                    } else {
                        closeMobileModal();
                    }
                } else {
                    // Reset posisi
                    $(this).css('transform', '');
                }
            });
        }
        
        // Bind swipe handler untuk modal dari session
        bindSwipeHandlers('#mobileReplaceCartModal');
        
        // Fungsi untuk menutup dynamic mobile modal dengan aman
        function closeDynamicMobileModal() {
            $('#dynamicMobileReplaceCartModal').css('transform', 'translateY(100%) !important').removeClass('show');
            
            setTimeout(function() {
                $('#dynamicMobileReplaceCartBackdrop').removeClass('show');
                setTimeout(function() {
                    $('#dynamicMobileReplaceCartModal').css('visibility', 'hidden');
                    $('#dynamicMobileReplaceCartModal, #dynamicMobileReplaceCartBackdrop').remove();
                    
                    // Kembalikan scroll position
                    if ($('body').hasClass('modal-open')) {
                        const scrollY = parseInt($('body').css('top') || '0') * -1;
                        $('body').removeClass('modal-open');
                        $('body').css('top', '');
                        window.scrollTo(0, scrollY);
                    }
                }, 200);
            }, 300);
        }

        // Add this function inside $(document).ready
        // Pastikan desktop-only disembunyikan pada tampilan mobile
        function updateButtonVisibility() {
            if (window.innerWidth <= 768) {
                $('.desktop-only').css('display', 'none');
                $('.mobile-buttons').css('display', 'flex');
            } else {
                $('.desktop-only').css('display', 'grid');
                $('.mobile-buttons').css('display', 'none');
            }
        }

        // Panggil saat halaman dimuat
        updateButtonVisibility();

        // Dan saat ukuran layar berubah
        $(window).resize(function() {
            updateButtonVisibility();
        });
        
        // Fungsi untuk mengirim request penggantian keranjang - dengan loading dan refresh
        function replacementRequest(productId, quantity) {
            // Use enhanced loading effect
            showPageLoading('Updating cart...');
            
            $.ajax({
                url: $('#addToCartForm').attr('action'),
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    replace_cart: 'yes',
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(response) {
                    // Update cart counter
                    $('.cart-counter').text(response.cartCount);
                    
                    // Remove desktop modal
                    $('#dynamicReplaceCartModal').remove();
                    
                    // Close mobile modal if open
                    closeDynamicMobileModal();
                    
                    // Hide loading with delay for better UX
                    setTimeout(function() {
                        hidePageLoading();
                        
                        // Show success message
                        showNotification(response.message, 'success');
                        
                        // Refresh page with loading effect
                        setTimeout(function() {
                            showPageLoading('Refreshing page...');
                            window.location.reload();
                        }, 1000);
                    }, 800);
                },
                error: function(xhr) {
                    // Hide loading
                    hidePageLoading();
                    
                    const response = xhr.responseJSON;
                    showNotification(response.message || 'Terjadi kesalahan', 'error');
                    
                    // Remove desktop modal
                    $('#dynamicReplaceCartModal').remove();
                    
                    // Close mobile modal if open
                    closeDynamicMobileModal();
                }
            });
        }

        // Fungsi untuk reinisialisasi komponen setelah refresh konten
        function reinitializeComponents() {
            // Reinitialize AOS
            AOS.refreshHard();
            
            // Reinitialize Slick Slider
            if ($('.product-images').length) {
                // Destroy existing slick
                if ($('.product-images').hasClass('slick-initialized')) {
                    $('.product-images').slick('unslick');
                }
                
                // Initialize new slick
                $('.product-images').slick({
                    dots: true,
                    arrows: true,
                    fade: true,
                    autoplay: false,
                    speed: 500,
                    cssEase: 'ease-in-out',
                    mobileFirst: true,
                    prevArrow: '<button class="slick-prev"><i class="fas fa-chevron-left"></i></button>',
                    nextArrow: '<button class="slick-next"><i class="fas fa-chevron-right"></i></button>',
                    responsive: [
                        {
                            breakpoint: 768,
                            settings: {
                                arrows: true
                            }
                        }
                    ]
                });
            }
            
            // Reattach thumbnail click events
            $('.thumbnail-item').click(function() {
                // Dapatkan index thumbnail yang diklik
                const index = $(this).index();
                
                // Tambahkan efek fade out ke gambar saat ini
                $('.product-images .slick-current img').animate({
                    opacity: 0.5
                }, 150, function() {
                    // Pindah ke slide yang sesuai
                    $('.product-images').slick('slickGoTo', index);
                    
                    // Fade in gambar baru
                    setTimeout(function() {
                        $('.product-images .slick-current img').animate({
                            opacity: 1
                        }, 150);
                    }, 100);
                });
            });
            
            // Reattach tab navigation
            $('.tab-btn').click(function() {
                $('.tab-btn').removeClass('active');
                $(this).addClass('active');
                
                $('.tab-content').addClass('hidden');
                $('#' + $(this).data('target')).removeClass('hidden');
            });
            
            // Update button visibility
            updateButtonVisibility();
        }
        
        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type) {
            // Hapus notifikasi yang mungkin sudah ada
            $('.notification-toast').remove();
            
            const notifClass = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            let position;
            
            // Posisi notifikasi berdasarkan perangkat
            if (window.innerWidth <= 768) {
                position = 'top-4 inset-x-4 text-center';
            } else {
                position = 'top-4 right-4';
            }
            
            const html = `
                <div class="notification-toast fixed ${position} z-50 ${notifClass} text-white px-4 py-2 rounded shadow-lg transition-opacity duration-300">
                    ${message}
                </div>
            `;
            
            const $notif = $(html).appendTo('body');
            
            // Otomatis hilangkan notifikasi setelah 1 detik (diubah dari 3 detik)
            setTimeout(function() {
                $notif.fadeTo(300, 0, function() {
                    $(this).remove();
                });
            }, 1000);
            
            // Tambahkan event click pada document untuk menghilangkan notifikasi
            $(document).one('click', function(e) {
                // Pastikan click bukan pada notifikasi itu sendiri
                if (!$(e.target).closest('.notification-toast').length) {
                    $notif.fadeTo(300, 0, function() {
                        $(this).remove();
                    });
                }
            });
        }
        
        // Window resize handler untuk beralih antara modal desktop dan mobile
        $(window).resize(function() {
            // Jika ada modal konflik seller yang sedang aktif dari session
            if ($('#replaceCartModal').hasClass('flex') || $('#mobileReplaceCartModal').hasClass('show')) {
                if (window.innerWidth <= 768) {
                    // Beralih ke modal mobile
                    $('#replaceCartModal').removeClass('flex').addClass('hidden');
                    showMobileModal();
                } else {
                    // Beralih ke modal desktop
                    closeMobileModal();
                    $('#replaceCartModal').removeClass('hidden').addClass('flex');
                }
            }
            
            // Jika ada modal konflik seller yang sedang aktif dari AJAX
            if ($('#dynamicReplaceCartModal').length || $('#dynamicMobileReplaceCartModal').length) {
                const productId = $('#dynamicReplaceCartModal').data('product-id') || $('#dynamicMobileReplaceCartModal').data('product-id');
                const quantity = $('#dynamicReplaceCartModal').data('quantity') || $('#dynamicMobileReplaceCartModal').data('quantity');
                const message = $('#dynamicReplaceCartModal .text-gray-700').first().text() || 
                            $('#dynamicMobileReplaceCartModal .text-gray-700').first().text();
                
                // Hapus modal yang ada
                $('#dynamicReplaceCartModal, #dynamicMobileReplaceCartModal, #dynamicMobileReplaceCartBackdrop').remove();
                
                // Kembalikan body ke normal jika dalam mode modal
                if ($('body').hasClass('modal-open')) {
                    const scrollY = parseInt($('body').css('top') || '0') * -1;
                    $('body').removeClass('modal-open');
                    $('body').css('top', '');
                    window.scrollTo(0, scrollY);
                }
                
                // Tampilkan modal yang sesuai dengan ukuran layar
                setTimeout(function() {
                    showSellerConflictModal(message, productId, quantity);
                }, 100);
            }
        });
        
        // Deteksi pull-to-refresh pada halaman
        let touchStartY = 0;
        let touchEndY = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStartY = e.touches[0].clientY;
        }, { passive: true });
        
        document.addEventListener('touchmove', function(e) {
            touchEndY = e.touches[0].clientY;
            
            // Jika pull-to-refresh di bagian atas halaman
            if ($(window).scrollTop() === 0 && touchStartY < 50 && touchEndY - touchStartY > 70) {
                // Tampilkan indikator pull-to-refresh
                $('.pull-to-refresh').addClass('visible');
            }
        }, { passive: true });
        
        document.addEventListener('touchend', function(e) {
            if ($(window).scrollTop() === 0 && touchStartY < 50 && touchEndY - touchStartY > 100) {
                // Show loading effect
                showPageLoading('Refreshing...');
                
                // Refresh halaman setelah pull cukup jauh
                setTimeout(function() {
                    window.location.reload();
                }, 500);
            } else {
                // Sembunyikan indikator jika tidak cukup jauh
                $('.pull-to-refresh').removeClass('visible');
            }
        }, { passive: true });
        
        // Pastikan mobile buttons terlihat pada perangkat mobile
        if (window.innerWidth <= 768) {
            $('.mobile-buttons').removeClass('hidden').css('display', 'flex');
        }
    });

    // Quantity controls
    function changeQuantity(change) {
        const input = document.querySelector('input[name="quantity"]');
        const newValue = parseInt(input.value) + change;
        const max = parseInt(input.max) || 10;
        
        if (newValue >= 1 && newValue <= max) {
            input.value = newValue;
        }
    }
</script>
@endsection