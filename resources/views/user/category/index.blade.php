@extends('user.layouts.app')

@section('styles')
<!-- Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- AOS - Animate On Scroll -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<style>
    .category-card {
        transition: all 0.3s ease;
        transform-origin: center;
    }
    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12);
    }
    .category-icon {
        transition: all 0.3s ease;
    }
    .category-card:hover .category-icon {
        transform: scale(1.2) rotate(15deg);
    }
    .gradient-overlay {
        background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.3));
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl font-bold text-gray-800 mb-4 flex items-center justify-center">
                <i class="fas fa-layer-group text-indigo-600 mr-4"></i>
                {{ $title }}
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Jelajahi berbagai kategori kue kami yang lezat dan beragam
            </p>
        </div>

        <!-- Categories Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('buyer.category.products', ['id' => $category->id]) }}" 
                   class="category-card block bg-white rounded-xl shadow-md overflow-hidden" 
                   data-aos="fade-up" 
                   data-aos-delay="{{ $loop->iteration * 100 }}">
                    
                    <div class="relative h-40 bg-gradient-to-br from-indigo-100 to-purple-100">
                        <div class="absolute inset-0 gradient-overlay"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="category-icon text-6xl text-indigo-600 opacity-80">
                                @switch($category->name)
                                    @case('Birthday Cakes')
                                        <span class="fas fa-birthday-cake"></span>
                                        @break
                                    @case('Wedding Cakes')
                                        <span class="fas fa-heart"></span>
                                        @break
                                    @case('Cupcakes')
                                        <span class="fas fa-cookie-bite"></span>
                                        @break
                                    @case('Pastries')
                                        <span class="fas fa-bread-slice"></span>
                                        @break
                                    @case('Donuts')
                                        <span class="fas fa-circle-notch"></span>
                                        @break
                                    @default
                                        <span class="fas fa-birthday-cake"></span>
                                @endswitch
                            </i>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">
                            {{ $category->name }}
                        </h3>
                        <p class="text-gray-600 text-sm mb-4">
                            {{ $category->products_count }} {{ $category->products_count == 1 ? 'produk' : 'produk' }}
                        </p>
                        <div class="flex justify-between items-center">
                            <span class="text-indigo-600 font-medium">
                                Lihat Semua
                            </span>
                            <i class="fas fa-arrow-right text-indigo-600"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($categories->isEmpty())
            <div class="text-center py-16" data-aos="fade-up">
                <i class="fas fa-layer-group text-6xl text-gray-300 mb-6"></i>
                <h2 class="text-2xl font-semibold text-gray-600 mb-4">
                    Belum Ada Kategori
                </h2>
                <p class="text-gray-500 mb-6">
                    Kategori produk belum tersedia saat ini.
                </p>
            </div>
        @endif
    </div>
</div>

<!-- AOS - Animate On Scroll -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Preloader dengan desain Tailwind dan transisi  
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
            }, 1000);  
        });
        
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });
    });
</script>
@endsection