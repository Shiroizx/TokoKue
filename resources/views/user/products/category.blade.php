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
    .category-badge {
        transition: all 0.3s ease;
    }
    .category-badge:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Category Header -->
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl font-bold text-gray-800 mb-4 flex items-center justify-center">
                <i class="fas fa-tag text-indigo-600 mr-4"></i>
                {{ $title }}
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Temukan berbagai produk berkualitas dalam kategori {{ $currentCategory->name }}
            </p>
        </div>

        <!-- Category Navigation -->
        <div class="mb-12 flex flex-wrap justify-center gap-4" data-aos="fade-up" data-aos-delay="200">
            @foreach($categories as $category)
                <a href="{{ route('buyer.products.category', $category->id) }}" 
                   class="category-badge px-4 py-2 rounded-full {{ $category->id == $currentCategory->id ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-indigo-100' }} transition-colors">
                    <i class="{{ $category->icon ?? 'fas fa-box' }} mr-2"></i>
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="product-card relative bg-white rounded-xl shadow-md overflow-hidden" 
                         data-aos="fade-up" 
                         data-aos-delay="{{ $loop->iteration * 100 }}">
                        <!-- Product Image -->
                        <div class="relative h-48 md:h-56 w-full">
                            @php
                                $primaryImage = $product->images()->where('is_primary', true)->first();
                            @endphp

                            <img src="{{ $primaryImage ? asset('storage/' . $primaryImage->image_path) : asset('images/default-product.png') }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover">
                            
                            <!-- Overlay -->
                            <div class="product-card-overlay">
                                <div class="flex space-x-4">
                                    <a href="{{ route('buyer.product.detail', $product->id) }}" 
                                       class="bg-white text-indigo-600 p-3 rounded-full hover:bg-indigo-600 hover:text-white transition-colors">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
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
        @else
            <!-- Empty State -->
            <div class="text-center py-16" data-aos="fade-up">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-6"></i>
                <h2 class="text-2xl font-semibold text-gray-600 mb-4">
                    Tidak Ada Produk
                </h2>
                <p class="text-gray-500 mb-6">
                    Maaf, tidak ada produk dalam kategori {{ $currentCategory->name }} saat ini.
                </p>
                <a href="{{ route('user.products') }}" 
                   class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Kembali ke Semua Produk
                </a>
            </div>
        @endif
    </div>
</div>

<!-- AOS - Animate On Scroll -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const preloader = document.createElement('div');  
        preloader.className = "fixed inset-0 bg-white flex items-center justify-center z-50 transition-opacity duration-500 ease-in-out";  
        preloader.innerHTML = `  
            <div class="text-center">  
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-pink-600 mx-auto mb-4"></div>  
                <p class="text-gray-600">Memuat halaman...</p>  
            </div>  
        `;  
        document.body.prepend(preloader);  

        // Fungsi untuk menghilangkan preloader dengan efek fade out  
        function removePreloader() {  
            // Tambahkan kelas opacity-0 untuk memulai fade out  
            preloader.classList.add('opacity-0');  

            // Tunggu animasi selesai sebelum menghapus elemen  
            setTimeout(() => {  
                preloader.remove();  
                // Tambahkan kelas untuk fade in produk  
                document.getElementById('products-container').classList.add('products-loaded');  
            }, 500); // Sesuaikan dengan durasi transisi  
        }  

        // Sembunyikan preloader setelah halaman selesai dimuat  
        window.addEventListener('load', function() {  
            // Tambahkan sedikit delay untuk efek visual yang lebih baik  
            setTimeout(() => {  
                removePreloader();  

                // Initialize AOS  
                AOS.init({  
                    duration: 800,  
                    once: true  
                });  
            }, 500);  
        });

        AOS.init({
            duration: 800,
            once: true
        });
    });
</script>
@endsection