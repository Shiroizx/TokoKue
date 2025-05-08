@extends('user.layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/images/galery/banner1.png');
            background-size: cover;
            background-position: center;
            height: 400px;
        }
        
        .value-card {
            transition: all 0.3s ease;
        }
        
        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .team-member-card {
            transition: all 0.3s ease;
        }
        
        .team-member-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .team-member-card img {
            transition: all 0.5s ease;
        }
        
        .team-member-card:hover img {
            transform: scale(1.05);
        }
        
        .timeline-container {
            position: relative;
        }
        
        .timeline-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            width: 4px;
            height: 100%;
            background: #EC4899;
            transform: translateX(-50%);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 60px;
        }
        
        .timeline-item::after {
            content: '';
            position: absolute;
            top: 20px;
            left: 50%;
            width: 20px;
            height: 20px;
            background: #EC4899;
            border-radius: 50%;
            transform: translateX(-50%);
        }
        
        .timeline-content {
            width: 45%;
            position: relative;
        }
        
        .timeline-content.left {
            margin-left: auto;
            padding-left: 40px;
        }
        
        .timeline-content.right {
            margin-right: auto;
            padding-right: 40px;
            text-align: right;
        }
        
        @media (max-width: 768px) {
            .timeline-container::before {
                left: 30px;
            }
            
            .timeline-item::after {
                left: 30px;
            }
            
            .timeline-content {
                width: 85%;
                margin-left: 60px !important;
                margin-right: 0 !important;
                text-align: left !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }
        
        .bg-pattern {
            background-color: #f9fafb;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f3f4f6' fill-opacity='0.7'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="hero-section flex items-center justify-center">
        <div class="text-center text-white" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Tentang Djawa Cake</h1>
            <p class="text-xl max-w-3xl mx-auto">Menyajikan Kelezatan Kue Tradisional Jawa dengan Sentuhan Modern Sejak 2015</p>
        </div>
    </div>
    
    <!-- Story Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="flex flex-col md:flex-row items-center gap-10">
            <div class="md:w-1/2" data-aos="fade-right">
                <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-book text-pink-600 mr-3"></i>
                    Cerita Kami
                </h2>
                <p class="text-gray-600 mb-4">
                    Djawa Cake bermula dari dapur kecil milik Bapak Arya di tahun 2015. Berbekal resep warisan keluarga yang telah diwariskan selama tiga generasi, Bapak Arya mulai menjual kue-kue tradisional Jawa kepada tetangga dan kerabat.
                </p>
                <p class="text-gray-600 mb-4">
                    Sambutan hangat dari pelanggan mendorong kami untuk membuka toko kecil di Jakarta. Kombinasi unik antara resep tradisional dengan sentuhan modern menjadikan Djawa Cake semakin dikenal dan digemari oleh masyarakat.
                </p>
                <p class="text-gray-600">
                    Kini, Djawa Cake telah berkembang menjadi salah satu toko kue terkemuka dengan 5 cabang di Jakarta. Kami tetap berpegang pada prinsip dasar: menggunakan bahan berkualitas, resep otentik, dan cinta dalam setiap kue yang kami buat.
                </p>
            </div>
            <div class="md:w-1/2" data-aos="fade-left">
                <img src="{{ asset('images/galery/banner2.png') }}" alt="Toko Djawa Cake" class="rounded-lg shadow-lg w-full">
            </div>
        </div>
    </div>
    
    <!-- Timeline -->
    <div class="bg-pattern py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center" data-aos="fade-up">
                <i class="fas fa-history text-pink-600 mr-3"></i>
                Perjalanan Kami
            </h2>
            
            <div class="timeline-container py-8">
                <!-- Timeline Item 1 -->
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-content right">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold text-pink-600 mb-2">2015</h3>
                            <p class="text-gray-600">Awal mula Djawa Cake dari dapur rumah Bapak Arya dengan modal resep keluarga dan semangat untuk melestarikan kue tradisional Jawa.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Item 2 -->
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-content left">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold text-pink-600 mb-2">2017</h3>
                            <p class="text-gray-600">Membuka toko pertama di Jakarta Utara, meraih respon positif dari komunitas lokal dan wisatawan yang mencari kue-kue otentik Jawa.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Item 3 -->
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-content right">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold text-pink-600 mb-2">2019</h3>
                            <p class="text-gray-600">Ekspansi ke Seluruh DKI Jakarta, memperkenalkan koleksi kue baru yang menggabungkan elemen modern dengan resep tradisional.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Item 4 -->
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-content left">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold text-pink-600 mb-2">2021</h3>
                            <p class="text-gray-600">Meluncurkan platform online untuk pemesanan dan pengiriman kue ke seluruh Indonesia, adaptasi di masa pandemi yang membawa Djawa Cake lebih dekat ke pelanggan.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Item 5 -->
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-content right">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold text-pink-600 mb-2">2023</h3>
                            <p class="text-gray-600">Pembukaan cabang ke-5 di Jakarta Pusat, serta peluncuran program kemitraan untuk mendukung UMKM di bidang kuliner tradisional.</p>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Item 6 -->
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-content left">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-bold text-pink-600 mb-2">Saat Ini</h3>
                            <p class="text-gray-600">Terus berinovasi dan melestarikan warisan kuliner Indonesia dengan memperkenalkan kelezatan kue tradisional Jawa ke pasar yang lebih luas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Values Section -->
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center" data-aos="fade-up">
            <i class="fas fa-heart text-pink-600 mr-3"></i>
            Nilai-Nilai Kami
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Value 1 -->
            <div class="value-card bg-white p-6 rounded-lg shadow-md text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-leaf text-2xl text-pink-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Kualitas Terbaik</h3>
                <p class="text-gray-600">Kami hanya menggunakan bahan-bahan terbaik dan segar untuk setiap kue yang kami buat, tanpa pengawet atau bahan kimia tambahan.</p>
            </div>
            
            <!-- Value 2 -->
            <div class="value-card bg-white p-6 rounded-lg shadow-md text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-mortar-pestle text-2xl text-pink-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Warisan Tradisional</h3>
                <p class="text-gray-600">Setiap kue kami dibuat dengan resep warisan yang telah diwariskan turun-temurun, menjaga keaslian rasa dan teknik pembuatan tradisional.</p>
            </div>
            
            <!-- Value 3 -->
            <div class="value-card bg-white p-6 rounded-lg shadow-md text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-seedling text-2xl text-pink-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Inovasi Berkelanjutan</h3>
                <p class="text-gray-600">Kami terus berinovasi untuk menghadirkan varian baru yang menggabungkan elemen tradisional dengan sentuhan modern yang sesuai selera masa kini.</p>
            </div>
        </div>
    </div>
    
    <!-- Team Section -->
    <div class="bg-pattern py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center" data-aos="fade-up">
                <i class="fas fa-users text-pink-600 mr-3"></i>
                Tim Kami
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
                <!-- Team Member 1 -->
                <div class="team-member-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="h-64 overflow-hidden">
                        <img src="{{ asset('images/arya.jpeg') }}" alt="Arya Satya - Founder" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">Arya Satya Nugraha</h3>
                        <p class="text-pink-600 font-medium mb-2">Founder & Master Baker</p>
                        <p class="text-gray-600 text-sm">Ahli kue tradisional dengan pengalaman lebih dari 10 tahun dalam dunia kuliner Jawa.</p>
                    </div>
                </div>
                
                <!-- Team Member 2 -->
                <div class="team-member-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                    <div class="h-64 overflow-hidden">
                        <img src="{{ asset('images/bowo.jpeg') }}" alt="Adit Prabowo - CEO" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">Adit Prabowo</h3>
                        <p class="text-pink-600 font-medium mb-2">CEO</p>
                        <p class="text-gray-600 text-sm">Memimpin ekspansi bisnis Djawa Cake dengan visi memperkenalkan kue tradisional ke pasar global.</p>
                    </div>
                </div>
                
                <!-- Team Member 3 -->
                <div class="team-member-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                    <div class="h-64 overflow-hidden">
                        <img src="{{ asset('images/fatur.jpeg') }}" alt="M Fatur Rohman - Head Pastry Chef" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">M Fatur Rohman</h3>
                        <p class="text-pink-600 font-medium mb-2">Head Pastry Chef</p>
                        <p class="text-gray-600 text-sm">Lulusan Oxford yang menggabungkan teknik modern dengan resep tradisional.</p>
                    </div>
                </div>
                
                <!-- Team Member 4 -->
                <div class="team-member-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                    <div class="h-64 overflow-hidden">
                        <img src="{{ asset('images/maman.jpeg') }}" alt="Abdul Rahman Majid - Marketing Director" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">Abdul Rahman Majid</h3>
                        <p class="text-pink-600 font-medium mb-2">Marketing Director</p>
                        <p class="text-gray-600 text-sm">Strategi pemasaran yang kreatif telah membawa Djawa Cake menjadi brand yang dikenal luas.</p>
                    </div>
                </div>
                
                <!-- Team Member 5 (Added) -->
                <div class="team-member-card bg-white rounded-lg shadow-md overflow-hidden" data-aos="fade-up" data-aos-delay="500">
                    <div class="h-64 overflow-hidden">
                        <img src="{{ asset('images/adil.jpeg') }}" alt="M Adlildzil A - Product Designer" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4 text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">M Adlildzil A</h3>
                        <p class="text-pink-600 font-medium mb-2">Head Product Designer</p>
                        <p class="text-gray-600 text-sm">Menciptakan tampilan unik dan menarik untuk produk-produk Djawa Cake yang memadukan estetika tradisional dan modern.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Testimonials -->
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center" data-aos="fade-up">
            <i class="fas fa-quote-left text-pink-600 mr-3"></i>
            Apa Kata Mereka
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-xl font-bold text-pink-600">A</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Adryan Maulana</h4>
                        <div class="text-yellow-400 flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Kue-kue Djawa Cake mengingatkan saya pada masa kecil di rumah nenek. Rasanya otentik dan kualitasnya selalu konsisten. Saya selalu memesan untuk acara-acara keluarga."
                </p>
            </div>
            
            <!-- Testimonial 2 -->
            <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="200">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-xl font-bold text-pink-600">R</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Restu Kelana Wahyudimas</h4>
                        <div class="text-yellow-400 flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Pertama kali mencoba kue dari Djawa Cake saat berkunjung ke Jakarta, dan sekarang selalu memesan online ke Jakarta. Rasa dan kualitasnya tidak pernah mengecewakan."
                </p>
            </div>
            
            <!-- Testimonial 3 -->
            <div class="bg-white p-6 rounded-lg shadow-md" data-aos="fade-up" data-aos-delay="300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-xl font-bold text-pink-600">D</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-800">Dimas Wijanarko</h4>
                        <div class="text-yellow-400 flex">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Sebagai pecinta kuliner tradisional, saya sangat mengapresiasi usaha Djawa Cake dalam melestarikan kue-kue Jawa dengan tetap mempertahankan kualitas dan keaslian rasa."
                </p>
            </div>
        </div>
    </div>
    
    <!-- Gallery -->
    <div class="bg-pattern py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center" data-aos="fade-up">
                <i class="fas fa-images text-pink-600 mr-3"></i>
                Galeri Kami
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="overflow-hidden rounded-lg shadow-md h-48 md:h-64" data-aos="zoom-in" data-aos-delay="100">
                    <img src="{{ asset('images/galery/galeri1.png') }}" alt="Gallery Image 1" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
                </div>
                <div class="overflow-hidden rounded-lg shadow-md h-48 md:h-64" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('images/galery/galeri2.png') }}" alt="Gallery Image 2" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
                </div>
                <div class="overflow-hidden rounded-lg shadow-md h-48 md:h-64" data-aos="zoom-in" data-aos-delay="300">
                    <img src="{{ asset('images/galery/galeri3.png') }}" alt="Gallery Image 3" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
                </div>
                <div class="overflow-hidden rounded-lg shadow-md h-48 md:h-64" data-aos="zoom-in" data-aos-delay="400">
                    <img src="{{ asset('images/galery/galeri4.png') }}" alt="Gallery Image 4" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
                </div>
                <div class="overflow-hidden rounded-lg shadow-md h-48 md:h-64" data-aos="zoom-in" data-aos-delay="100">
                    <img src="{{ asset('images/galery/galeri5.png') }}" alt="Gallery Image 5" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
                </div>
                <div class="overflow-hidden rounded-lg shadow-md h-48 md:h-64" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('images/galery/galeri6.png') }}" alt="Gallery Image 6" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
                </div>
                <div class="overflow-hidden rounded-lg shadow-md h-48 md:h-64" data-aos="zoom-in" data-aos-delay="300">
                    <img src="{{ asset('images/galery/galeri7.png') }}" alt="Gallery Image 7" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
                </div>
                <div class="overflow-hidden rounded-lg shadow-md h-48 md:h-64" data-aos="zoom-in" data-aos-delay="400">
                    <img src="{{ asset('images/galery/galeri8.png') }}" alt="Gallery Image 8" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-500">
                </div>
            </div>
        </div>
    </div>
    
    <!-- CTA Section -->
    <div class="container mx-auto px-4 py-16">
        <div class="bg-gradient-to-r from-pink-500 to-pink-700 rounded-2xl overflow-hidden shadow-xl" data-aos="fade-up">
            <div class="p-8 md:p-12 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Temukan Kelezatan Djawa Cake</h2>
                <p class="text-white text-opacity-90 mb-8 max-w-3xl mx-auto">
                    Kunjungi toko kami atau pesan online untuk menikmati kelezatan kue-kue tradisional Jawa yang dibuat dengan cinta dan bahan-bahan berkualitas.
                </p>
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="#" class="bg-white text-pink-600 font-medium px-6 py-3 rounded-md hover:bg-gray-100 transition-colors duration-300 flex items-center justify-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Kunjungi Toko
                    </a>
                    <a href="#" class="bg-transparent border-2 border-white text-white font-medium px-6 py-3 rounded-md hover:bg-white hover:bg-opacity-10 transition-colors duration-300 flex items-center justify-center">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Belanja Online
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Section -->
    <div class="bg-white py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-800 mb-12 text-center" data-aos="fade-up">
                <i class="fas fa-envelope text-pink-600 mr-3"></i>
                Hubungi Kami
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div data-aos="fade-right">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Informasi Kontak</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-pink-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Alamat Toko Pusat</h4>
                                <p class="text-gray-600">Jl. Kramat No. 98, DKI Jakarta 10450, Indonesia</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-phone-alt text-pink-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Telepon</h4>
                                <p class="text-gray-600">(+62) 851-1234-5678</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-envelope text-pink-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Email</h4>
                                <p class="text-gray-600">info@djawacake.id</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-clock text-pink-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-1">Jam Operasional</h4>
                                <p class="text-gray-600">Senin - Sabtu: 08.00 - 20.00 WIB</p>
                                <p class="text-gray-600">Minggu: 10.00 - 18.00 WIB</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-share-alt text-pink-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800 mb-2">Sosial Media</h4>
                                <div class="flex space-x-3">
                                    <a href="#" class="text-gray-600 hover:text-pink-600 transition-colors duration-300">
                                        <i class="fab fa-instagram text-xl"></i>
                                    </a>
                                    <a href="#" class="text-gray-600 hover:text-pink-600 transition-colors duration-300">
                                        <i class="fab fa-facebook text-xl"></i>
                                    </a>
                                    <a href="#" class="text-gray-600 hover:text-pink-600 transition-colors duration-300">
                                        <i class="fab fa-twitter text-xl"></i>
                                    </a>
                                    <a href="#" class="text-gray-600 hover:text-pink-600 transition-colors duration-300">
                                        <i class="fab fa-tiktok text-xl"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div data-aos="fade-left">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Kirim Pesan</h3>
                    
                    <form action="#" method="POST" class="bg-gray-50 p-6 rounded-lg shadow-md">
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-medium mb-2">Nama</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="subject" class="block text-gray-700 font-medium mb-2">Subjek</label>
                            <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" required>
                        </div>
                        
                        <div class="mb-6">
                            <label for="message" class="block text-gray-700 font-medium mb-2">Pesan</label>
                            <textarea id="message" name="message" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent" required></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-pink-600 text-white py-3 px-6 rounded-md font-medium hover:bg-pink-700 transition-colors duration-300">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map Section -->
    <div class="py-16">
        <div class="container mx-auto px-4 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center" data-aos="fade-up">
                <i class="fas fa-map-marked-alt text-pink-600 mr-3"></i>
                Lokasi Kami
            </h2>
            <p class="text-gray-600 text-center max-w-2xl mx-auto mb-8" data-aos="fade-up">
                Temukan toko Djawa Cake terdekat dan kunjungi kami untuk merasakan langsung kelezatan kue-kue tradisional Jawa.
            </p>
        </div>
        
        <!-- Embed map placeholder - in production, replace with an actual Google Maps embed -->
        <div class="w-full h-96" data-aos="fade-up">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63465.7427935385!2d106.7666943486328!3d-6.183064000000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f529e6d018f1%3A0xaff453c7ca93130e!2sUniversitas%20BSI%20Kampus%20Kramat%2098!5e0!3m2!1sid!2sid!4v1746615052165!5m2!1sid!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
    
    <!-- Footer (Placeholder - Link this to your actual footer template) -->

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>

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
        
        // Initialize AOS animation library
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endsection