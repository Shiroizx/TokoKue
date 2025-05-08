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
    /* Loading Overlay */  
    .loading-overlay {  
        position: fixed;  
        top: 0;  
        left: 0;  
        width: 100%;  
        height: 100%;  
        background-color: rgba(255, 255, 255, 0.8);  
        display: flex;  
        justify-content: center;  
        align-items: center;  
        z-index: 9999;  
        backdrop-filter: blur(5px);  
    }  

    .loading-spinner {  
        width: 80px;  
        height: 80px;  
        border: 10px solid #f3f3f3;  
        border-top: 10px solid #3498db;  
        border-radius: 50%;  
        animation: spin 1s linear infinite;  
    }  

    @keyframes spin {  
        0% { transform: rotate(0deg); }  
        100% { transform: rotate(360deg); }  
    }  

    .product-card {  
        opacity: 0;  
        transition: opacity 0.5s ease;  
    }  

    .products-loaded .product-card {  
        opacity: 1;  
    }
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
    .filter-section {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.8);
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
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl font-bold text-gray-800 mb-4 flex items-center justify-center">
                <i class="fas fa-cookie-bite text-indigo-600 mr-4"></i>
                {{ $title }}
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Temukan berbagai macam kue lezat yang siap memanjakan lidah Anda
            </p>
        </div>

        <!-- Search and Sort Section -->
        <div class="mb-12 sticky top-0 z-20" data-aos="fade-up" data-aos-delay="200">
            <div class="filter-section rounded-xl shadow-md p-4 flex justify-center items-center gap-4">
                <!-- Search -->
                <div class="relative flex-grow max-w-md">
                    <input type="text" 
                           id="product-search" 
                           placeholder="Cari produk..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>

                <!-- Sort -->
                <div class="relative">
                    <select id="product-sort" 
                            class="pl-4 pr-10 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Urutkan</option>
                        <option value="price-asc">Harga Terendah</option>
                        <option value="price-desc">Harga Tertinggi</option>
                        <option value="newest">Terbaru</option>
                    </select>
                    <i class="fas fa-sort absolute right-3 top-3 text-gray-400"></i>
                </div>
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

        <!-- Empty State (jika tidak ada produk) -->
        @if($products->isEmpty())
            <div class="text-center py-16" data-aos="fade-up">
                <i class="fas fa-box-open text-6xl text-gray-300 mb-6"></i>
                <h2 class="text-2xl font-semibold text-gray-600 mb-4">
                    Tidak Ada Produk
                </h2>
                <p class="text-gray-500 mb-6">
                    Maaf, saat ini tidak ada produk yang tersedia.
                </p>
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

        // Search Functionality
        const searchInput = document.getElementById('product-search');
        const sortSelect = document.getElementById('product-sort');
        const productsContainer = document.getElementById('products-container');
        const productCards = productsContainer.querySelectorAll('.product-card');

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


        function filterProducts() {
            const searchTerm = searchInput.value.toLowerCase();
            const sortValue = sortSelect.value;

            // Convert NodeList to Array for sorting
            const productArray = Array.from(productCards);

            // Filter by search
            const filteredProducts = productArray.filter(card => {
                const name = card.dataset.name.toLowerCase();
                return name.includes(searchTerm);
            });

            // Sort products
            if (sortValue === 'price-asc') {
                filteredProducts.sort((a, b) => 
                    parseFloat(a.dataset.price) - parseFloat(b.dataset.price)
                );
            } else if (sortValue === 'price-desc') {
                filteredProducts.sort((a, b) => 
                    parseFloat(b.dataset.price) - parseFloat(a.dataset.price)
                );
            } else if (sortValue === 'newest') {
                filteredProducts.sort((a, b) => 
                    new Date(b.dataset.created) - new Date(a.dataset.created)
                );
            }

            // Clear and re-append filtered products
            productsContainer.innerHTML = '';
            filteredProducts.forEach(card => productsContainer.appendChild(card));
        }

        searchInput.addEventListener('input', filterProducts);
        sortSelect.addEventListener('change', filterProducts);
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

    // Show notification
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg ${
            type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        } text-white transform transition-transform duration-300`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('translate-y-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
</script>
@endsection