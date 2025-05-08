@extends('user.layouts.app')

@section('styles')
<!-- Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- AOS - Animate On Scroll -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- Custom Styles -->
<style>
    .product-card {
        transition: all 0.3s ease;
        transform-origin: center;
    }
    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
    }
    .product-card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .product-card:hover .product-card-overlay {
        opacity: 1;
    }
    .product-card-overlay a {
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.3s ease;
    }
    .product-card:hover .product-card-overlay a {
        transform: translateY(0);
        opacity: 1;
    }
    .wishlist-btn {
        transition: all 0.3s ease;
    }
    .wishlist-btn:hover {
        transform: scale(1.1);
    }
    .wishlist-active {
        color: #dc2626 !important;
    }
    .category-banner {
        background: linear-gradient(135deg, #4158D0 0%, #C850C0 100%);
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Category Banner -->
        <div class="category-banner rounded-xl p-8 mb-12 text-white" data-aos="fade-up">
            <div class="max-w-3xl">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('buyer.home') }}" class="text-white hover:text-gray-200">
                                <i class="fas fa-home mr-2"></i>Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-white mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <a href="{{ route('buyer.categories.index') }}" class="text-white hover:text-gray-200">Categories</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-white mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-200">{{ $category->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                
                <h1 class="text-4xl font-bold mb-4">{{ $category->name }}</h1>
                <p class="text-gray-200 text-lg">
                    {{ $category->description ?? 'Temukan berbagai produk terbaik dalam kategori ini' }}
                </p>
            </div>
        </div>

        <!-- Products Grid -->
        <div id="products-container" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="product-card relative bg-white rounded-xl shadow-md overflow-hidden" 
                     data-aos="fade-up" 
                     data-aos-delay="{{ $loop->iteration * 100 }}"
                     data-name="{{ $product->name }}"
                     data-price="{{ $product->price }}"
                     data-created="{{ $product->created_at }}">
                    
                    <!-- Product Image -->
                    <div class="relative h-48 md:h-56 w-full">
                        @php
                            $primaryImage = $product->images()->where('is_primary', true)->first() 
                                            ?? $product->images()->first();
                            $isInWishlist = auth()->user() && auth()->user()->hasInWishlist($product->id);
                        @endphp
                        <img src="{{ $primaryImage ? asset('storage/' . $primaryImage->image_path) : asset('images/default-product.png') }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                        
                        <!-- Wishlist Button -->
                        <button onclick="toggleWishlist(event, {{ $product->id }})" 
                                class="wishlist-btn absolute top-3 right-3 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md hover:shadow-lg z-10 {{ $isInWishlist ? 'wishlist-active' : '' }}">
                            <i class="fas fa-heart"></i>
                        </button>
                        
                        <!-- Overlay -->
                        <div class="product-card-overlay">
                            <a href="{{ route('buyer.product.detail', $product->id) }}" 
                               class="flex items-center justify-center bg-white text-indigo-600 px-6 py-3 rounded-full hover:bg-indigo-600 hover:text-white transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye mr-2"></i>
                                <span class="font-semibold">Lihat Detail</span>
                            </a>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2 truncate">
                            {{ $product->name }}
                        </h3>
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-600 font-bold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-box-open mr-1"></i>
                                {{ $product->stock }} tersedia
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-12" data-aos="fade-up">
                {{ $products->links() }}
            </div>
        @endif

        <!-- Empty State -->
        @if($products->isEmpty())
            <div class="text-center py-16" data-aos="fade-up">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-6"></i>
                <h2 class="text-2xl font-semibold text-gray-600 mb-4">
                    Belum Ada Produk
                </h2>
                <p class="text-gray-500 mb-6">
                    Kategori ini belum memiliki produk saat ini.
                </p>
                <a href="{{ route('buyer.products') }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg inline-block transition-colors">
                    Lihat Semua Produk
                </a>
            </div>
        @endif
    </div>
</div>

<!-- AOS - Animate On Scroll -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });
    });

    // Wishlist Toggle
    function toggleWishlist(event, productId) {
        // Check if user is logged in
        @if(!auth()->check())
            event.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Login Diperlukan',
                text: 'Silakan login terlebih dahulu untuk menambahkan produk ke wishlist',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Login Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("login") }}';
                }
            });
            return;
        @else
            console.log('Starting toggleWishlist with product ID:', productId);
            console.log('Event:', event);
            
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
                console.log('Response:', response);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                console.log('Success data:', data);
                
                if(data.status === 'success') {
                    // Sekarang event.target ada karena dipass ke fungsi
                    const wishlistBtn = event.target.closest('.wishlist-btn');
                    
                    if(data.action === 'added') {
                        wishlistBtn.classList.add('wishlist-active');
                    } else {
                        wishlistBtn.classList.remove('wishlist-active');
                    }
                    
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
                console.error('Error:', error);
                
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
</script>
@endsection