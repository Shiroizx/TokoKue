@extends('user.layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<!-- Page Loading Animation -->
<div id="page-loader" class="fixed inset-0 bg-white z-50 flex items-center justify-center transition-opacity duration-500">
    <div class="flex flex-col items-center">
        <div class="relative">
            <svg class="animate-spin h-16 w-16 text-pink-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div class="absolute inset-0 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
        </div>
        <p class="mt-4 text-gray-600 font-medium">Memuat Keranjang...</p>
        <div class="mt-3 w-48 h-1 bg-gray-200 rounded-full overflow-hidden">
            <div class="loading-progress h-full bg-gradient-to-r from-pink-400 to-pink-600 rounded-full"></div>
        </div>
    </div>
</div>
<!-- Breadcrumb with subtle gradient -->
<div class="bg-gradient-to-r from-gray-50 to-gray-100 py-4 shadow-sm">
    <div class="container mx-auto px-4">
        <nav class="flex text-sm items-center">
            <a href="{{ route('buyer.home') }}" class="text-gray-600 hover:text-pink-500 transition-colors duration-200">
                <i class="fas fa-home mr-1"></i> Beranda
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-pink-600 font-medium">Keranjang Belanja</span>
        </nav>
    </div>
</div>

<!-- Cart Content -->
<section class="py-12" data-aos="fade-up" data-aos-duration="800">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Keranjang Belanja
            </h1>
            <span class="hidden md:inline-flex px-3 py-1 bg-pink-100 text-pink-600 text-sm rounded-full">
                <span id="cart-count">{{ $cartItems->count() }}</span> item
            </span>
        </div>
        
        <!-- Alert Messages with animations -->
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm animate__animated animate__fadeIn" role="alert">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm animate__animated animate__fadeIn" role="alert">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p>{{ session('error') }}</p>
                </div>
            </div>
        @endif
        
        @if($cartItems->count() > 0)
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 transition-all duration-300 hover:shadow-lg" data-aos="fade-up" data-aos-delay="100">
                <!-- Cart Table Header (Desktop) -->
                <div class="hidden md:flex border-b border-gray-200 px-6 py-4 bg-gray-50 text-gray-700 font-medium">
                    <div class="w-2/5">Produk</div>
                    <div class="w-1/5 text-center">Harga</div>
                    <div class="w-1/5 text-center">Jumlah</div>
                    <div class="w-1/5 text-center">Subtotal</div>
                </div>
                
                <!-- Cart Items -->
                @foreach($cartItems as $index => $item)
                <div class="border-b border-gray-200 last:border-b-0 px-6 py-6 flex flex-col md:flex-row hover:bg-gray-50 transition-colors duration-200" 
                     data-aos="fade-up" data-aos-delay="{{ 150 + ($index * 50) }}">
                    <!-- Product (Mobile & Desktop) -->
                    <div class="md:w-2/5 flex">
                        <div class="w-24 h-24 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden shadow-sm transition-transform duration-300 transform hover:scale-105">
                            @if($item->product->primaryImage())
                                <img src="{{ asset('storage/' . $item->product->primaryImage()->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 flex flex-col justify-between">
                            <div>
                                <h3 class="text-base font-medium text-gray-800 hover:text-pink-600 transition-colors duration-200">{{ $item->product->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ Str::limit($item->product->description ?? '', 60) }}</p>
                            </div>
                            <!-- Mobile Only Price -->
                            <div class="md:hidden mt-2 text-sm text-gray-600">
                                <span class="font-medium text-pink-500">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Price (Desktop) -->
                    <div class="hidden md:flex md:w-1/5 items-center justify-center">
                        <span class="text-gray-700 font-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    </div>
                    
                    <!-- Quantity -->
                    <div class="flex items-center justify-between mt-4 md:mt-0 md:w-1/5 md:justify-center">
                        <span class="md:hidden text-sm text-gray-600">Jumlah:</span>
                        <div class="flex items-center">
                            <form action="{{ route('buyer.cart.update-quantity', $item->id) }}" method="POST" class="flex items-center">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="quantity-decrease px-2 py-1 bg-gray-100 border border-gray-300 rounded-l-md hover:bg-pink-100 hover:text-pink-600 focus:outline-none transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                    </svg>
                                </button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" 
                                       class="quantity-input w-14 px-2 py-1 text-center border border-gray-300 focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500"
                                       data-item-id="{{ $item->id }}" data-price="{{ $item->price }}">
                                <button type="button" class="quantity-increase px-2 py-1 bg-gray-100 border border-gray-300 rounded-r-md hover:bg-pink-100 hover:text-pink-600 focus:outline-none transition-colors duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                                <button type="submit" class="update-btn ml-2 px-2 py-1 bg-pink-100 text-pink-600 rounded hover:bg-pink-200 transition-colors duration-200" title="Update">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>                    
                    
                    <!-- Subtotal & Remove -->
                    <div class="flex items-center justify-between mt-4 md:mt-0 md:w-1/5 md:justify-center">
                        <div class="flex flex-col items-start md:items-center">
                            <span class="md:hidden text-sm text-gray-600">Subtotal:</span>
                            <span class="item-subtotal text-pink-600 font-medium">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                        <form action="{{ route('buyer.cart.remove', $item->id) }}" method="POST" class="ml-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-item text-gray-400 hover:text-red-500 focus:outline-none transition-colors duration-200" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
                
                <!-- Cart Summary with sticky position -->
                <div class="px-6 py-6 bg-gradient-to-r from-gray-50 to-gray-100 relative">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-col space-y-2" data-aos="fade-right" data-aos-delay="200">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                    </svg>
                                    Subtotal
                                </span>
                                <span class="font-medium cart-subtotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                    Estimasi Ongkir
                                </span>
                                <span class="text-gray-600 italic text-sm">Dihitung saat checkout</span>
                            </div>
                            <div class="border-t border-gray-200 pt-2 mt-1"></div>
                        </div>
                        <div class="mt-6 md:mt-0" data-aos="fade-left" data-aos-delay="300">
                            <div class="text-right">
                                <div class="text-lg font-bold text-gray-800 mb-1">Total</div>
                                <div class="text-2xl font-bold text-pink-600 cart-total">Rp {{ number_format($total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Buttons with enhanced styles -->
            <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-between" data-aos="fade-up" data-aos-delay="400">
                <a href="{{ route('buyer.home') }}" class="mt-4 sm:mt-0 px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 shadow-sm hover:shadow transition-all duration-200 flex items-center justify-center group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500 group-hover:text-pink-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Lanjutkan Belanja
                </a>
                <a href="{{ route('buyer.shipping') }}" class="px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-md hover:from-pink-600 hover:to-pink-700 shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center group">
                    Lanjut ke Pengiriman
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        @else
            <!-- Empty Cart State with animation -->
            <div class="bg-white rounded-xl shadow-md p-12 text-center border border-gray-100" data-aos="zoom-in" data-aos-duration="800">
                <div class="mb-6 inline-flex items-center justify-center w-24 h-24 bg-pink-100 text-pink-500 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">Keranjang Anda Kosong</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Silakan tambahkan beberapa produk ke keranjang belanja Anda untuk melanjutkan proses checkout.</p>
                <a href="{{ route('buyer.products') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-md hover:from-pink-600 hover:to-pink-700 shadow-md hover:shadow-lg transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Mulai Belanja
                </a>
            </div>
        @endif
        
        <!-- Shopping Tips Section -->
        <div class="mt-12 bg-white rounded-xl shadow-sm p-6 border border-gray-100" data-aos="fade-up" data-aos-delay="500">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Tips Belanja
            </h3>
            <div class="grid md:grid-cols-3 gap-4 text-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-medium text-gray-800">Transaksi Aman</h4>
                        <p class="text-gray-500 mt-1">Pembayaran dan data pribadi Anda selalu terlindungi</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-medium text-gray-800">Beragam Metode Pembayaran</h4>
                        <p class="text-gray-500 mt-1">Transfer bank, e-wallet, dan pembayaran instan</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="font-medium text-gray-800">Pengiriman Cepat</h4>
                        <p class="text-gray-500 mt-1">Opsi pengiriman cepat untuk berbagai wilayah</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Include AOS Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<!-- Include Animate.css for subtle animations -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            once: true, // animations will happen only once
            duration: 800, // values from 0 to 3000, with step 50ms
            easing: 'ease-out-cubic', // default easing for AOS animations
        });
        
        // Quantity adjustment handlers
        const decreaseButtons = document.querySelectorAll('.quantity-decrease');
        const increaseButtons = document.querySelectorAll('.quantity-increase');
        const quantityInputs = document.querySelectorAll('.quantity-input');
        
        // Function to update subtotal
        function updateSubtotal(input) {
            const quantity = parseInt(input.value);
            const price = parseFloat(input.dataset.price);
            const itemId = input.dataset.itemId;
            const subtotalElement = input.closest('div.flex-col, div.flex-row').querySelector('.item-subtotal');
            
            if (subtotalElement) {
                const subtotal = price * quantity;
                subtotalElement.textContent = formatCurrency(subtotal);
            }
            
            // Update cart total
            updateCartTotal();
        }
        
        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
        }
        
        // Update cart total
        function updateCartTotal() {
            let total = 0;
            quantityInputs.forEach(input => {
                const quantity = parseInt(input.value);
                const price = parseFloat(input.dataset.price);
                total += price * quantity;
            });
            
            // Update subtotal and total displayed
            const subtotalElements = document.querySelectorAll('.cart-subtotal');
            const totalElements = document.querySelectorAll('.cart-total');
            
            subtotalElements.forEach(el => {
                el.textContent = formatCurrency(total);
            });
            
            totalElements.forEach(el => {
                el.textContent = formatCurrency(total);
            });
        }
        
        // Decrease button handler
        decreaseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                let value = parseInt(input.value);
                
                if (value > 1) {
                    input.value = value - 1;
                    updateSubtotal(input);
                    
                    // Add animation
                    this.classList.add('animate__animated', 'animate__pulse');
                    setTimeout(() => {
                        this.classList.remove('animate__animated', 'animate__pulse');
                    }, 500);
                }
            });
        });
        
        // Increase button handler
        increaseButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                let value = parseInt(input.value);
                let max = parseInt(input.getAttribute('max')) || 100;
                
                if (value < max) {
                    input.value = value + 1;
                    updateSubtotal(input);
                    
                    // Add animation
                    this.classList.add('animate__animated', 'animate__pulse');
                    setTimeout(() => {
                        this.classList.remove('animate__animated', 'animate__pulse');
                    }, 500);
                }
            });
        });
        
        // Input change handler
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                let value = parseInt(this.value);
                let min = parseInt(this.getAttribute('min')) || 1;
                let max = parseInt(this.getAttribute('max')) || 100;
                
                if (value < min) {
                    this.value = min;
                    value = min;
                } else if (value > max) {
                    this.value = max;
                    value = max;
                }
                
                updateSubtotal(this);
            });
        });
        
        // Remove item with animation
        const removeButtons = document.querySelectorAll('.remove-item');
        
        removeButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const itemContainer = this.closest('.border-b');
                
                // Add fade-out animation before submitting the form
                if (itemContainer) {
                    e.preventDefault();
                    itemContainer.classList.add('animate__animated', 'animate__fadeOutRight');
                    
                    setTimeout(() => {
                        this.closest('form').submit();
                    }, 500);
                }
            });
        });
        
        // Update button with animation
        const updateButtons = document.querySelectorAll('.update-btn');
        
        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.classList.add('animate__animated', 'animate__rotateIn');
                
                setTimeout(() => {
                    this.classList.remove('animate__animated', 'animate__rotateIn');
                }, 500);
            });
        });
        
        // Log for debugging
        console.log('Modern cart initialized successfully');
    });
</script>

<!-- Additional styles -->
<style>
    /* Hide number input arrows */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    input[type=number] {
        -moz-appearance: textfield;
    }
    
    /* Smooth hover transitions */
    .transition-all {
        transition: all 0.3s ease;
    }
    
    /* Custom scrollbar for browsers that support it */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #d1d1d1;
    }
    
    /* Line-clamp for multi-line text truncation */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Pulse animation for buttons */
    @keyframes pulse-border {
        0% { box-shadow: 0 0 0 0 rgba(236, 72, 153, 0.4); }
        70% { box-shadow: 0 0 0 6px rgba(236, 72, 153, 0); }
        100% { box-shadow: 0 0 0 0 rgba(236, 72, 153, 0); }
    }
    
    .pulse-animation {
        animation: pulse-border 1.5s infinite;
    }
    
    /* Custom focus styles */
    .focus-visible:focus-visible {
        outline: 2px solid rgba(236, 72, 153, 0.5);
        outline-offset: 2px;
    }
    
    /* Badge animation */
    @keyframes badge-pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
    }
    
    .badge-pulse {
        animation: badge-pulse 2s infinite;
    }
    
    /* Shimmer effect for loading states */
    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
    }
    
    .shimmer {
        background: linear-gradient(to right, #f6f7f8 8%, #edeef1 18%, #f6f7f8 33%);
        background-size: 2000px 100%;
        animation: shimmer 2s infinite linear;
    }
    
    /* Float animation for cart icon */
    @keyframes float {
        0% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
        100% { transform: translateY(0); }
    }
    
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
    
    /* Glass morphism effect */
    .glass-effect {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Responsive font sizes */
    @media (max-width: 640px) {
        .text-responsive {
            font-size: calc(1rem + 0.5vw);
        }
    }
    
    /* Custom checkmark animation */
    @keyframes checkmark {
        0% { transform: scale(0); opacity: 0; }
        50% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .checkmark-animation {
        animation: checkmark 0.5s ease-in-out forwards;
    }
    
    /* Loading bar animation */
    @keyframes loading-progress {
        0% { width: 0%; }
        20% { width: 20%; }
        40% { width: 40%; }
        60% { width: 60%; }
        80% { width: 80%; }
        100% { width: 100%; }
    }
    
    .loading-progress {
        width: 0%;
        animation: loading-progress 2s ease-in-out forwards;
    }
    
    /* Cart items loading animation */
    @keyframes fade-in-up {
        0% { 
            opacity: 0;
            transform: translateY(20px);
        }
        100% { 
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in-up {
        animation: fade-in-up 0.5s ease-out forwards;
    }
    
    /* Staggered animation delay utility classes */
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
</style>

<!-- Floating cart actions button (mobile only) -->
<div class="md:hidden fixed bottom-6 right-6 z-50">
    <button id="floating-cart-btn" class="bg-pink-600 text-white rounded-full w-12 h-12 shadow-lg flex items-center justify-center pulse-animation">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 float-animation" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
    </button>
</div>

<!-- Mobile cart actions modal -->
<div id="mobile-cart-actions" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden flex items-end justify-center">
    <div class="bg-white w-full max-w-md rounded-t-xl p-6 animate__animated animate__slideInUp">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Aksi Keranjang</h3>
            <button id="close-cart-actions" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="space-y-3">
            <a href="{{ route('buyer.home') }}" class="flex items-center justify-between w-full px-4 py-3 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <span class="flex items-center text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Lanjutkan Belanja
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
            <a href="#" class="flex items-center justify-between w-full px-4 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700">
                <span class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                    Lanjut ke Checkout
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</div>

<script>
    // Page loading animation
    document.addEventListener('DOMContentLoaded', function() {
        // Handle page loading animation
        const pageLoader = document.getElementById('page-loader');
        
        if (pageLoader) {
            // If the page is already loaded when this runs (not a refresh)
            if (document.readyState === 'complete') {
                setTimeout(() => {
                    pageLoader.style.opacity = '0';
                    setTimeout(() => {
                        pageLoader.style.display = 'none';
                    }, 500);
                }, 500);
            } else {
                // For fresh page loads and refreshes
                window.addEventListener('load', () => {
                    setTimeout(() => {
                        pageLoader.style.opacity = '0';
                        setTimeout(() => {
                            pageLoader.style.display = 'none';
                        }, 500);
                    }, 500);
                });
            }
        }
        
        // Add staggered animations to cart items after loading
        const cartItems = document.querySelectorAll('.border-b.border-gray-200');
        cartItems.forEach((item, index) => {
            item.classList.add('opacity-0');
            
            setTimeout(() => {
                item.classList.add('fade-in-up');
                item.classList.add(`delay-${(index % 5 + 1) * 100}`);
                item.classList.remove('opacity-0');
            }, 1000); // Start after page loader is gone
        });
        
        // Additional script for mobile floating button and modal
        const floatingBtn = document.getElementById('floating-cart-btn');
        const mobileActions = document.getElementById('mobile-cart-actions');
        const closeBtn = document.getElementById('close-cart-actions');
        
        if (floatingBtn && mobileActions && closeBtn) {
            floatingBtn.addEventListener('click', function() {
                mobileActions.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            });
            
            closeBtn.addEventListener('click', function() {
                mobileActions.classList.add('hidden');
                document.body.style.overflow = ''; // Re-enable scrolling
            });
            
            // Close when clicking outside the modal
            mobileActions.addEventListener('click', function(e) {
                if (e.target === mobileActions) {
                    mobileActions.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        }
        
        // Add nice micro-interaction to add to cart button
        const updateBtns = document.querySelectorAll('.update-btn');
        updateBtns.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.classList.add('scale-110');
            });
            
            btn.addEventListener('mouseleave', function() {
                this.classList.remove('scale-110');
            });
        });
        
        // Add a sticky behavior to the cart summary on scroll
        const cartSummary = document.querySelector('.bg-gradient-to-r.from-gray-50.to-gray-100');
        if (cartSummary && window.innerWidth >= 768) { // Only on desktop
            window.addEventListener('scroll', function() {
                const cartContainer = document.querySelector('.bg-white.rounded-xl.shadow-md');
                if (cartContainer) {
                    const containerRect = cartContainer.getBoundingClientRect();
                    if (containerRect.bottom < window.innerHeight && containerRect.top < 0) {
                        cartSummary.classList.add('sticky', 'bottom-0', 'shadow-lg', 'z-10');
                    } else {
                        cartSummary.classList.remove('sticky', 'bottom-0', 'shadow-lg', 'z-10');
                    }
                }
            });
        }
    });
</script>
@endsection