@extends('user.layouts.app')

@section('title', 'Beranda')

@section('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.css" />
<style>
    .hero-section {
        background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('images/banner2.jpg') }}');
        background-size: cover;
        background-position: center;
        height: 500px;
    }
    
    .category-card:hover .category-icon {
        transform: translateY(-5px);
    }
    
    .category-icon {
        transition: transform 0.3s ease;
    }
    
    /* Styling untuk product card */
    .product-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
    }
    
    .product-card:hover .product-overlay {
        opacity: 1;
    }
    
    /* Overlay yang menutupi seluruh gambar */
    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.9);
        opacity: 0;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 20; /* Z-index lebih tinggi dari absolute link area */
    }
    
    .product-overlay-content {
        text-align: center;
        transform: translateY(20px);
        transition: all 0.3s ease;
        z-index: 30; /* Z-index lebih tinggi lagi untuk tombol */
    }
    
    .product-card:hover .product-overlay-content {
        transform: translateY(0);
    }
    
    /* Tombol pada overlay */
    .product-overlay a {
        position: relative;
        z-index: 40;
        pointer-events: auto;
    }
    
    /* Tombol add-to-cart harus lebih tinggi z-index dari link area */
    .add-to-cart {
        position: relative;
        z-index: 30;
        pointer-events: auto;
    }
    
    /* Mengatur pointer untuk menunjukkan area yang bisa diklik */
    .product-card a.absolute {
        cursor: pointer;
        z-index: 10;
        pointer-events: auto;
    }
    
    /* Membuat link area tidak mengoverride tombol dan link lainnya */
    .product-card a.absolute:hover ~ .product-overlay {
        opacity: 1;
    }
    
    /* Pastikan judul dan tombol lain tetap dapat diklik */
    .product-card h3 a, 
    .product-card .add-to-cart {
        position: relative;
        z-index: 25;
    }
    
    .swiper {
        width: 100%;
        height: 100%;
        padding-bottom: 50px;
    }
    
    .testimonial-card {
        background: white;
        border-radius: 0.5rem;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .swiper-pagination-bullet-active {
        background-color: #ec4899 !important;
    }
    
    /* Bounce animation for CTA buttons */
    .btn-bounce:hover {
        animation: bounce 1s;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
        40% {transform: translateY(-10px);}
        60% {transform: translateY(-5px);}
    }
    
    /* Shining effect for featured products */
    .shine-effect {
        position: relative;
        overflow: hidden;
    }
    
    .shine-effect::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
        transform: rotate(30deg);
        transition: all 0.6s;
        opacity: 0;
    }
    
    .shine-effect:hover::after {
        animation: shine 1.5s ease-in-out;
    }
    
    @keyframes shine {
        0% {
            opacity: 0;
            transform: rotate(30deg) translate(-300px, -300px);
        }
        30% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            transform: rotate(30deg) translate(300px, 300px);
        }
    }
    
    .badge-new {
        position: absolute;
        top: 1rem;
        left: 0;
        background-color: #ec4899;
        color: white;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: bold;
        z-index: 10;
        transform: rotate(-2deg);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .badge-new::after {
        content: '';
        position: absolute;
        right: -8px;
        top: 0;
        width: 0;
        height: 0;
        border-top: 13px solid transparent;
        border-left: 8px solid #ec4899;
        border-bottom: 13px solid transparent;
    }
    
    .carousel-img {
        object-fit: cover;
        height: 500px;
        width: 100%;
    }
</style>
@endsection

@section('content')
    <!-- Hero Carousel Section -->
    <div class="relative w-full swiper hero-swiper" data-aos="fade-in">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="hero-section flex items-center justify-center">
                    <div class="text-center text-white p-8">
                        <h1 class="text-5xl font-bold mb-4" data-aos="fade-up" data-aos-delay="200">Nikmati Kue Berkualitas Premium</h1>
                        <p class="text-xl mb-8" data-aos="fade-up" data-aos-delay="400">Dibuat dengan bahan-bahan pilihan untuk momen spesial Anda</p>
                        <a href="{{ route('buyer.products') }}" class="btn-bounce bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition inline-flex items-center" data-aos="fade-up" data-aos-delay="600">
                            <i class="fas fa-shopping-bag mr-2"></i> Belanja Sekarang
                        </a>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="relative">
                    <img src="{{ asset('images/banner1.jpg') }}" alt="Kue Premium" class="carousel-img">
                    <div class="absolute inset-0 flex items-center bg-black bg-opacity-40">
                        <div class="container mx-auto px-6 text-white">
                            <h2 class="text-4xl font-bold mb-4">Kue untuk Semua Acara</h2>
                            <p class="text-xl mb-6">Pernikahan, Ulang Tahun, atau sekedar untuk dinikmati bersama</p>
                            <a href="{{ route('buyer.products') }}" class="btn-bounce bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-6 rounded-lg shadow-lg transition inline-flex items-center">
                                <i class="fas fa-birthday-cake mr-2"></i> Eksplor Koleksi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-2 text-center" data-aos="fade-up">Kategori Kue</h2>
            <p class="text-gray-600 text-center mb-10" data-aos="fade-up" data-aos-delay="200">Temukan berbagai pilihan kue untuk setiap momen spesial</p>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6">
                @foreach($categories as $category)
                <a href="{{ route('buyer.products.category', $category->id) }}" class="category-card bg-orange-50 hover:bg-orange-100 transition p-6 rounded-xl flex flex-col items-center text-center shadow-sm hover:shadow-md" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="w-20 h-20 rounded-full bg-orange-100 flex items-center justify-center mb-4 category-icon">
                        @if($loop->iteration % 5 == 1)
                            <i class="fas fa-birthday-cake text-pink-600 text-2xl"></i>
                        @elseif($loop->iteration % 5 == 2)
                            <i class="fas fa-cookie text-pink-600 text-2xl"></i>
                        @elseif($loop->iteration % 5 == 3)
                            <i class="fas fa-ice-cream text-pink-600 text-2xl"></i>
                        @elseif($loop->iteration % 5 == 4)
                            <i class="fas fa-cheese text-pink-600 text-2xl"></i>
                        @else
                            <i class="fas fa-bread-slice text-pink-600 text-2xl"></i>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-800 text-lg">{{ $category->name }}</h3>
                    <span class="text-sm text-pink-600 mt-2 inline-flex items-center">
                        Lihat Produk <i class="fas fa-arrow-right ml-1"></i>
                    </span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gradient-to-b from-orange-50 to-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-2 text-center" data-aos="fade-up">Mengapa Memilih Kami?</h2>
            <p class="text-gray-600 text-center mb-10" data-aos="fade-up" data-aos-delay="200">Kami berkomitmen untuk memberikan produk dan layanan terbaik</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="flex flex-col items-center text-center p-8 rounded-xl bg-white shadow-md" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-pink-100 p-5 rounded-full mb-6 text-pink-600">
                        <i class="fas fa-star-of-life text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Kue Premium</h3>
                    <p class="text-gray-600">Dibuat dengan bahan-bahan berkualitas tinggi dan resep yang telah teruji untuk menghasilkan cita rasa terbaik.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="flex flex-col items-center text-center p-8 rounded-xl bg-white shadow-md" data-aos="fade-up" data-aos-delay="500">
                    <div class="bg-blue-100 p-5 rounded-full mb-6 text-blue-600">
                        <i class="fas fa-truck-moving text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Pengiriman Cepat</h3>
                    <p class="text-gray-600">Layanan pengiriman cepat dan aman untuk menjaga kualitas dan kesegaran kue agar sampai ke tangan Anda dengan sempurna.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="flex flex-col items-center text-center p-8 rounded-xl bg-white shadow-md" data-aos="fade-up" data-aos-delay="700">
                    <div class="bg-green-100 p-5 rounded-full mb-6 text-green-600">
                        <i class="fas fa-shield-alt text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Jaminan Kualitas</h3>
                    <p class="text-gray-600">Kami menjamin kualitas dan kepuasan Anda dengan setiap produk yang kami buat. Tidak puas? Kami ganti dengan yang baru!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold mb-2" data-aos="fade-right">Produk Unggulan</h2>
                    <p class="text-gray-600" data-aos="fade-right" data-aos-delay="200">Kue terbaik pilihan kami untuk Anda</p>
                </div>
                <a href="{{ route('buyer.products') }}" class="text-pink-600 hover:text-pink-700 font-medium inline-flex items-center" data-aos="fade-left">
                    Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                <div class="product-card bg-white rounded-xl shadow-md overflow-hidden shine-effect" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    @if($loop->iteration <= 2)
                    <div class="badge-new">Baru</div>
                    @endif
                    
                    <div class="relative overflow-hidden">
                        <!-- Image container -->
                        @if($product->primaryImage())
                            <img src="{{ asset('storage/' . $product->primaryImage()->image_path) }}" 
                                 alt="{{ $product->name }}" class="w-full h-60 object-cover transform hover:scale-105 transition duration-500">
                        @else
                            <img src="{{ asset('images/no-image.jpg') }}" 
                                 alt="{{ $product->name }}" class="w-full h-60 object-cover transform hover:scale-105 transition duration-500">
                        @endif
                        
                        <!-- Overlay dengan tombol "Lihat Detail" yang bisa diklik -->
                        <div class="product-overlay">
                            <div class="product-overlay-content w-full">
                                <a href="{{ route('buyer.product.detail', $product->id) }}" 
                                   class="bg-pink-600 text-white rounded-full py-2 px-4 hover:bg-pink-700 transition inline-flex items-center justify-center z-30 relative">
                                    <i class="fas fa-eye mr-2"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-2">
                            <!-- Product title -->
                            <h3 class="font-semibold text-gray-800 text-lg truncate">{{ $product->name }}</h3>
                            <div class="flex items-center text-amber-400 ml-2">
                                <i class="fas fa-star text-sm"></i>
                                <span class="text-gray-700 text-sm ml-1">4.8</span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $product->description }}</div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-pink-600 text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <!-- Tambahkan link area yang menutupi seluruh produk tapi tidak termasuk tombol -->
                    <a href="{{ route('buyer.product.detail', $product->id) }}" class="absolute inset-0 z-10" aria-label="View product details"></a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-2 text-center" data-aos="fade-up">Apa Kata Pelanggan Kami</h2>
            <p class="text-gray-600 text-center mb-10" data-aos="fade-up" data-aos-delay="200">Pendapat jujur dari pelanggan yang telah mencoba produk kami</p>
            
            <div class="swiper testimonial-swiper" data-aos="fade-up" data-aos-delay="400">
                <div class="swiper-wrapper">
                    <!-- Testimonial 1 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-full bg-pink-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-pink-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold">Gilang Ramadhan</h4>
                                    <div class="flex text-amber-400 text-sm">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="italic text-gray-600">"Kue yang saya pesan untuk acara ulang tahun anak saya sangat lezat dan cantik! Semua tamu memuji tampilannya dan rasanya yang pas. Terima kasih Djawa Cake!"</p>
                        </div>
                    </div>
                    
                    <!-- Testimonial 2 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold">Haykal Alvito Wibowo</h4>
                                    <div class="flex text-amber-400 text-sm">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="italic text-gray-600">"Saya sudah mencoba banyak toko kue, tapi Djawa Cake adalah yang terbaik. Kuenya lembut, tidak terlalu manis, dan selalu datang tepat waktu. Pelayanannya juga sangat ramah!"</p>
                        </div>
                    </div>
                    
                    <!-- Testimonial 3 -->
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center mr-4">
                                    <i class="fas fa-user text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold">Risel Sujaya</h4>
                                    <div class="flex text-amber-400 text-sm">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="italic text-gray-600">"Kue ulang tahun pernikahan yang saya pesan sangat istimewa dan sesuai dengan harapan. Detail hiasannya sangat rapi dan indah. Pasangan saya sangat terkejut dan senang!"</p>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>

    <!-- Products By Category with Tabs -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-2 text-center" data-aos="fade-up">Pilihan Kue Berdasarkan Kategori</h2>
            <p class="text-gray-600 text-center mb-10" data-aos="fade-up" data-aos-delay="200">Temukan kue sesuai dengan kategori favorit Anda</p>
            
            <!-- Category Tabs -->
            <div class="mb-10 flex flex-wrap justify-center" data-aos="fade-up" data-aos-delay="400">
                <button class="category-tab active px-6 py-2 rounded-full bg-pink-600 text-white mr-2 mb-2 focus:outline-none transition" data-category="all">
                    Semua
                </button>
                @foreach($categories as $category)
                <button class="category-tab px-6 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-pink-100 hover:text-pink-600 mr-2 mb-2 focus:outline-none transition" data-category="{{ $category->id }}">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
            
            <!-- Product Listing by Tabs -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 category-products" id="all-products">
                @foreach($categories as $category)
                    @php
                        $categoryProducts = $products->where('category_id', $category->id)->take(4);
                    @endphp
                    
                    @foreach($categoryProducts as $product)
                    <div class="product-card bg-white rounded-xl shadow-md overflow-hidden category-product shine-effect" data-category="{{ $product->category_id }}" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div class="relative overflow-hidden">
                            @if($product->primaryImage())
                                <img src="{{ asset('storage/' . $product->primaryImage()->image_path) }}" 
                                     alt="{{ $product->name }}" class="w-full h-60 object-cover transform hover:scale-105 transition duration-500">
                            @else
                                <img src="{{ asset('images/no-image.jpg') }}" 
                                     alt="{{ $product->name }}" class="w-full h-60 object-cover transform hover:scale-105 transition duration-500">
                            @endif
                            
                            <!-- Overlay dengan tombol "Lihat Detail" -->
                            <div class="product-overlay">
                                <div class="product-overlay-content">
                                    <a href="{{ route('buyer.products.show', $product->id) }}" class="bg-pink-600 text-white rounded-full py-2 px-4 hover:bg-pink-700 transition inline-flex items-center">
                                        <i class="fas fa-eye mr-2"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-semibold text-gray-800 text-lg truncate">{{ $product->name }}</h3>
                            </div>
                            <div class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $product->description }}</div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-pink-600 text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <!-- Link area untuk seluruh card -->
                        <a href="{{ route('buyer.products.show', $product->id) }}" class="absolute inset-0 z-10" aria-label="Lihat detail {{ $product->name }}"></a>
                    </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-16 bg-gradient-to-r from-pink-500 to-pink-700 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4" data-aos="fade-up">Buat Momen Spesial Anda Lebih Berkesan</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="200">Pesan kue spesial untuk acara pernikahan, ulang tahun, atau momen penting lainnya dan buat kenangan tak terlupakan.</p>
            <div class="flex flex-col sm:flex-row justify-center gap-4" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('buyer.products') }}" class="btn-bounce bg-white text-pink-600 hover:bg-gray-100 font-bold py-3 px-8 rounded-lg shadow-lg transition inline-flex items-center justify-center">
                    <i class="fas fa-shopping-bag mr-2"></i> Pesan Sekarang
                </a>
                <a href="#" class="btn-bounce bg-transparent border-2 border-white hover:bg-white hover:text-pink-600 font-bold py-3 px-8 rounded-lg shadow-lg transition inline-flex items-center justify-center">
                    <i class="fas fa-phone-alt mr-2"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter Section with Improved Design -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="fade-up">
                <div class="grid md:grid-cols-2">
                    <div class="p-10 flex items-center justify-center bg-pink-600">
                        <div class="text-center text-white">
                            <i class="fas fa-envelope-open-text text-5xl mb-4"></i>
                            <h3 class="text-2xl font-bold mb-2">Dapatkan Update Terbaru</h3>
                            <p class="mb-0">Promo khusus, diskon, dan info menarik lainnya</p>
                        </div>
                    </div>
                    <div class="p-10">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Berlangganan Newsletter</h3>
                        <p class="text-gray-600 mb-6">Dapatkan informasi terbaru tentang produk, promo, dan tips dari Sweet Cake. Kami tidak akan mengirimkan spam!</p>
                        
                        <form action="#" method="POST">
                            @csrf
                            <div class="mb-4">
                                <input type="email" name="email" placeholder="Masukkan alamat email Anda" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent">
                            </div>
                            <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-4 rounded-lg transition flex items-center justify-center">
                                <i class="fas fa-paper-plane mr-2"></i> Berlangganan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/8.4.5/swiper-bundle.min.js"></script>
    <script>
        // Initialize AOS animation
        AOS.init({
            duration: 800,
            once: true
        });
        
        // Initialize Hero Swiper
        const heroSwiper = new Swiper('.hero-swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
        
        // Initialize Testimonial Swiper
        const testimonialSwiper = new Swiper('.testimonial-swiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
        });
        
        // Category tabs functionality
        const categoryTabs = document.querySelectorAll('.category-tab');
        const categoryProducts = document.querySelectorAll('.category-product');
        
        categoryTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                categoryTabs.forEach(t => {
                    t.classList.remove('active', 'bg-pink-600', 'text-white');
                    t.classList.add('bg-gray-200', 'text-gray-700');
                });
                
                // Add active class to current tab
                this.classList.add('active', 'bg-pink-600', 'text-white');
                this.classList.remove('bg-gray-200', 'text-gray-700');
                
                const selectedCategory = this.getAttribute('data-category');
                
                // Show/hide products based on category
                categoryProducts.forEach(product => {
                    if (selectedCategory === 'all' || product.getAttribute('data-category') === selectedCategory) {
                        product.style.display = 'block';
                        // Re-trigger AOS for visible elements
                        setTimeout(() => {
                            AOS.refresh();
                        }, 50);
                    } else {
                        product.style.display = 'none';
                    }
                });
            });
        });
        
        // Add to Cart Functionality
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Prevent triggering parent link click
                
                const productId = this.dataset.id;
                
                // Add loading effect to button
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;
                
                // Send AJAX request to add item to cart
                fetch('{{ route("buyer.cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button state
                    this.innerHTML = '<i class="fas fa-cart-plus"></i>';
                    this.disabled = false;
                    
                    if(data.success) {
                        // Update cart counter if exists
                        const cartCountElement = document.querySelector('.cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cartCount || '0';
                        }
                        
                        // Show success notification
                        showNotification('Produk berhasil ditambahkan ke keranjang', 'success');
                        
                        // Add subtle animation to cart icon
                        const cartIcon = document.querySelector('.fa-shopping-cart');
                        if (cartIcon) {
                            cartIcon.classList.add('animate-bounce');
                            setTimeout(() => {
                                cartIcon.classList.remove('animate-bounce');
                            }, 1000);
                        }
                    } else {
                        showNotification(data.message || 'Gagal menambahkan produk ke keranjang', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.innerHTML = '<i class="fas fa-cart-plus"></i>';
                    this.disabled = false;
                    showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                });
            });
        });
        
        // Notification Function with improved design
        function showNotification(message, type) {
            // Remove any existing notifications
            const existingNotifications = document.querySelectorAll('.notification-toast');
            existingNotifications.forEach(notification => {
                notification.remove();
            });
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification-toast fixed bottom-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 flex items-center ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white max-w-xs transform transition-all duration-500 translate-y-20 opacity-0`;
            
            // Add icon based on type
            const icon = document.createElement('i');
            icon.className = `mr-3 text-xl ${type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'}`;
            notification.appendChild(icon);
            
            // Add message
            const messageElement = document.createElement('div');
            messageElement.className = 'flex-1';
            messageElement.textContent = message;
            notification.appendChild(messageElement);
            
            // Append to body
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-y-20', 'opacity-0');
            }, 10);
            
            // Animate out after delay
            setTimeout(() => {
                notification.classList.add('translate-y-20', 'opacity-0');
                
                // Remove from DOM after animation completes
                setTimeout(() => {
                    notification.remove();
                }, 500);
            }, 3000);
        }
        
        // Lazy loading for images
        const productImages = document.querySelectorAll('.product-card img');
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const image = entry.target;
                        const dataSrc = image.getAttribute('data-src');
                        if (dataSrc) {
                            image.src = dataSrc;
                            image.removeAttribute('data-src');
                        }
                        observer.unobserve(image);
                    }
                });
            });
            
            productImages.forEach(image => {
                if (!image.complete) {
                    // Only observe images that haven't loaded yet
                    imageObserver.observe(image);
                }
            });
        }
        
        // Initialize floating WhatsApp button
        const whatsappButton = document.createElement('a');
        whatsappButton.href = "https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20bertanya%20tentang%20produk%20kue%20Anda";
        whatsappButton.target = "_blank";
        whatsappButton.className = "fixed bottom-24 right-4 bg-green-500 hover:bg-green-600 text-white rounded-full w-14 h-14 flex items-center justify-center shadow-lg z-40 transition-transform hover:scale-110";
        whatsappButton.innerHTML = '<i class="fab fa-whatsapp text-2xl"></i>';
        document.body.appendChild(whatsappButton);
        
        // Add back-to-top button
        const backToTopButton = document.createElement('button');
        backToTopButton.className = "fixed bottom-4 right-4 bg-pink-600 hover:bg-pink-700 text-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg z-40 transition-transform hover:scale-110 opacity-0";
        backToTopButton.innerHTML = '<i class="fas fa-arrow-up"></i>';
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        document.body.appendChild(backToTopButton);
        
        // Show/hide back-to-top button based on scroll position
        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                backToTopButton.classList.remove('opacity-0');
                backToTopButton.classList.add('opacity-100');
            } else {
                backToTopButton.classList.remove('opacity-100');
                backToTopButton.classList.add('opacity-0');
            }
        });
        
        // Add preloader
        const preloader = document.createElement('div');
        preloader.className = "fixed inset-0 bg-white flex items-center justify-center z-50";
        preloader.innerHTML = `
            <div class="text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-pink-600 mx-auto mb-4"></div>
                <p class="text-gray-600">Memuat halaman...</p>
            </div>
        `;
        document.body.prepend(preloader);
        
        // Hide preloader when page is loaded
        window.addEventListener('load', () => {
            setTimeout(() => {
                preloader.style.opacity = '0';
                preloader.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    preloader.remove();
                }, 500);
            }, 500);
        });
    </script>
@endsection