<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Sweet Cake</title>
    @yield('head')
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .cake-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .cake-card {
            transition: all 0.3s ease;
        }
    </style>
    @yield('styles')
</head>
<body class="bg-orange-50">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="#" class="flex items-center">
                        <img src="{{ asset('images/logo1.jpg') }}" alt="Sweet Cake Logo" class="h-10 w-auto">
                        <span class="ml-2 text-2xl font-bold text-pink-600">Djawa Cake</span>
                    </a>
                </div>
                
                <!-- Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('buyer.home') }}" class="text-gray-700 hover:text-pink-600 transition">Beranda</a>
                    <a href="{{ route('buyer.products') }}" class="text-gray-700 hover:text-pink-600 transition">Produk</a>
                    <a href="{{ route('buyer.categories.index') }}" class="text-gray-700 hover:text-pink-600 transition">Kategori</a>
                    <a href="{{ route('aboutus') }}" class="text-gray-700 hover:text-pink-600 transition">Tentang Kami</a>
                    <a href="{{ route('contact.index') }}" class="text-gray-700 hover:text-pink-600 transition">Kontak</a>
                </nav>
                
                <!-- Cart and User Menu -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('buyer.cart.index') }}" class="text-gray-700 hover:text-pink-600 transition relative">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="cart-counter absolute -top-2 -right-2 bg-pink-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">
                            {{ \App\Models\Cart::where(Auth::check() ? 'user_id' : 'session_id', Auth::check() ? Auth::id() : session()->getId())->sum('quantity') }}
                        </span>
                    </a>
                    
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-pink-600 transition focus:outline-none">
                                <span class="ml-2">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('buyer.profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Profil</a>
                                <a href="{{ route('buyer.orders.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Pesanan Saya</a>
                                <a href="{{ route('buyer.wishlist.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Wishlist</a>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-pink-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-pink-600 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 transition">Daftar</a>
                    @endauth
                    
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden text-gray-700 hover:text-pink-600 transition focus:outline-none" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="py-3 px-4 space-y-3">
                    <a href="{{ route('buyer.home') }}" class="block text-gray-700 hover:text-pink-600 transition">Beranda</a>
                    <a href="{{ route('buyer.products') }}" class="block text-gray-700 hover:text-pink-600 transition">Produk</a>
                    <a href="{{ route('buyer.categories.index') }}" class="block text-gray-700 hover:text-pink-600 transition">Kategori</a>
                    <a href="{{ route('aboutus') }}" class="block text-gray-700 hover:text-pink-600 transition">Tentang Kami</a>
                    <a href="{{ route('contact.index') }}" class="block text-gray-700 hover:text-pink-600 transition">Kontak</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Djawa Cake</h3>
                    <p class="text-gray-300 mb-4">Menyediakan kue tradisional dan modern berkualitas untuk berbagai acara spesial Anda. Dibuat dengan bahan-bahan premium dan penuh cinta.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-pink-400 transition"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white hover:text-pink-400 transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white hover:text-pink-400 transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white hover:text-pink-400 transition"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Kue Ulang Tahun</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Cupcake</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Kue Pernikahan</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Pastry</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Kue Tradisional</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Tautan</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Tentang Kami</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">FAQ</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-pink-400 transition">Hubungi Kami</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-3 text-pink-400"></i>
                            <span>Jl. Kramat No. 98, Jakarta Pusat, Indonesia</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-3 text-pink-400"></i>
                            <span>+62 851-1234-5678</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-pink-400"></i>
                            <span>info@djawacake.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <hr class="border-gray-700 my-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p>&copy; {{ date('Y') }} Sweet Cake. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>