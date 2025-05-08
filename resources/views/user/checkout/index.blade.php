@extends('user.layouts.app')

@section('title', 'Pilih Pengiriman')

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
        </div>
        <p class="mt-4 text-gray-600 font-medium">Memuat Halaman Pengiriman...</p>
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
            <a href="{{ route('buyer.cart.index') }}" class="text-gray-600 hover:text-pink-500 transition-colors duration-200">
                Keranjang Belanja
            </a>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-pink-600 font-medium">Pilih Pengiriman</span>
        </nav>
    </div>
</div>

<!-- Checkout Steps -->
<div class="bg-white py-4 border-b border-gray-200 shadow-sm mb-6">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div class="hidden md:flex w-full max-w-3xl mx-auto">
                <div class="w-1/3 text-center">
                    <div class="relative">
                        <div class="h-10 w-10 bg-pink-500 text-white rounded-full flex items-center justify-center mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div class="mt-2 text-sm font-medium text-gray-700">Keranjang</div>
                    </div>
                </div>
                <div class="w-1/3 text-center">
                    <div class="relative">
                        <div class="absolute left-0 top-5 w-1/2 h-1 bg-pink-500"></div>
                        <div class="absolute right-0 top-5 w-1/2 h-1 bg-gray-300"></div>
                        <div class="h-10 w-10 bg-pink-500 text-white rounded-full flex items-center justify-center mx-auto relative z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="mt-2 text-sm font-medium text-gray-900">Pengiriman</div>
                    </div>
                </div>
                <div class="w-1/3 text-center">
                    <div class="relative">
                        <div class="absolute left-0 top-5 w-1/2 h-1 bg-gray-300"></div>
                        <div class="h-10 w-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div class="mt-2 text-sm font-medium text-gray-600">Pembayaran</div>
                    </div>
                </div>
            </div>
            <!-- Mobile Steps -->
            <div class="flex md:hidden w-full justify-between text-xs">
                <div class="text-center">
                    <div class="h-8 w-8 bg-pink-500 text-white rounded-full flex items-center justify-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div class="mt-1 font-medium">Keranjang</div>
                </div>
                <div class="text-center">
                    <div class="h-8 w-8 bg-pink-500 text-white rounded-full flex items-center justify-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="mt-1 font-medium">Pengiriman</div>
                </div>
                <div class="text-center">
                    <div class="h-8 w-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div class="mt-1 font-medium">Pembayaran</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shipping Section -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col-reverse lg:flex-row gap-8">
            <!-- Shipping Form -->
            <div class="w-full lg:w-2/3" data-aos="fade-up" data-aos-duration="800">
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 mb-6">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Alamat Pengiriman
                        </h2>
                        
                        <!-- Alert Messages -->
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

                        <!-- Informasi lokasi produk -->
                        <div class="bg-gray-50 rounded-lg p-3 mb-4 border border-gray-100">
                            @php
                                // Ambil kode kota dari environment variable
                                $originCityId = env('RAJAONGKIR_ORIGIN_CITY', '501');
                                
                                // Array kota-kota di Indonesia yang umum digunakan
                                $cities = [
                                    '151' => 'Jakarta',
                                    '152' => 'Jakarta Pusat',
                                    '153' => 'Jakarta Barat',
                                    '154' => 'Jakarta Selatan',
                                    '155' => 'Jakarta Timur',
                                    '501' => 'Jakarta Utara',
                                ];
                                
                                // Ambil nama kota berdasarkan ID, atau gunakan nilai default jika tidak ditemukan
                                $originCityName = isset($cities[$originCityId]) ? $cities[$originCityId] : "Kota {$originCityId}";
                            @endphp
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-700">Produk akan dikirim dari <span class="font-medium text-pink-600">{{ $originCityName }}</span></p>
                                    <p class="text-xs text-gray-500 mt-1">Pengiriman dilakukan melalui ekspedisi pilihan Anda</p>
                                </div>
                            </div>
                        </div>
                        
                        <form id="shipping-form" action="{{ route('buyer.shipping.process') }}" method="POST">
                            @csrf
                            
                            <!-- Display current address if available -->
                            @if($user->address)
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <h3 class="text-md font-semibold text-gray-800 mb-2">Alamat Tersimpan</h3>
                                    <p class="text-gray-700">{{ $user->address }}</p>
                                    @if($user->city && $user->province)
                                        <p class="text-gray-600 mt-1">{{ $user->city }}, {{ $user->province }}, {{ $user->postal_code ?? '' }}</p>
                                    @endif
                                    <p class="text-sm text-pink-600 mt-2">Anda akan mengupdate alamat ini dengan alamat baru yang Anda masukkan di bawah.</p>
                                </div>
                            @endif
                            
                            <!-- Recipient Information -->
                            <div class="border border-gray-200 rounded-lg p-4 mb-6">
                                <h3 class="text-md font-semibold text-gray-800 mb-3">Informasi Penerima</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Penerima</label>
                                        <input type="text" id="recipient_name" name="recipient_name" value="{{ $user->name ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-colors duration-200" placeholder="Masukkan nama penerima" required>
                                    </div>
                                    
                                    <div>
                                        <label for="recipient_phone" class="block text-sm font-medium text-gray-700 mb-1">No. WhatsApp Penerima</label>
                                        <input type="text" id="recipient_phone" name="recipient_phone" value="{{ $user->phone ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-colors duration-200" placeholder="Contoh: 08123456789" required>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Address Form -->
                            <div class="border border-gray-200 rounded-lg p-4 mb-6">
                                <h3 class="text-md font-semibold text-gray-800 mb-3">Alamat Pengiriman</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="province_id" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                        <select id="province_id" name="province_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-colors duration-200" required>
                                            <option value="">Pilih Provinsi</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province['province_id'] }}" data-name="{{ $province['province'] }}" {{ $user->province_id == $province['province_id'] ? 'selected' : '' }}>{{ $province['province'] }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="province_name" id="province_name" value="{{ $user->province ?? '' }}">
                                    </div>
                                    
                                    <div>
                                        <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                                        <select id="city_id" name="city_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-colors duration-200" {{ $user->city_id ? '' : 'disabled' }} required>
                                            <option value="">Pilih Kota/Kabupaten</option>
                                            @if($user->city_id)
                                                <option value="{{ $user->city_id }}" selected>{{ $user->city }}</option>
                                            @endif
                                        </select>
                                        <input type="hidden" name="city_name" id="city_name" value="{{ $user->city ?? '' }}">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="district" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                                        <input type="text" id="district" name="district" value="{{ $user->district ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-colors duration-200" placeholder="Masukkan kecamatan" required>
                                    </div>
                                    
                                    <div>
                                        <label for="village" class="block text-sm font-medium text-gray-700 mb-1">Kelurahan/Desa</label>
                                        <input type="text" id="village" name="village" value="{{ $user->village ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-colors duration-200" placeholder="Masukkan kelurahan/desa" required>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                                        <input type="text" id="postal_code" name="postal_code" value="{{ $user->pos ?? '' }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-colors duration-200" placeholder="Masukkan kode pos" required>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Tempat Tinggal</label>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                            <div class="relative">
                                                <input type="radio" id="residence-house" name="residence_type" value="house" class="hidden residence-radio" checked>
                                                <label for="residence-house" class="block p-3 border rounded-lg cursor-pointer transition-colors duration-200 residence-label border-pink-500 bg-pink-50 text-center">
                                                    <div class="font-medium text-gray-800">Rumah</div>
                                                </label>
                                            </div>
                                            
                                            <div class="relative">
                                                <input type="radio" id="residence-apartment" name="residence_type" value="apartment" class="hidden residence-radio">
                                                <label for="residence-apartment" class="block p-3 border rounded-lg cursor-pointer transition-colors duration-200 residence-label border-gray-200 hover:border-pink-300 text-center">
                                                    <div class="font-medium text-gray-800">Apartemen</div>
                                                </label>
                                            </div>
                                            
                                            <div class="relative">
                                                <input type="radio" id="residence-kos" name="residence_type" value="kos" class="hidden residence-radio">
                                                <label for="residence-kos" class="block p-3 border rounded-lg cursor-pointer transition-colors duration-200 residence-label border-gray-200 hover:border-pink-300 text-center">
                                                    <div class="font-medium text-gray-800">Kos</div>
                                                </label>
                                            </div>
                                            
                                            <div class="relative">
                                                <input type="radio" id="residence-rent" name="residence_type" value="rent" class="hidden residence-radio">
                                                <label for="residence-rent" class="block p-3 border rounded-lg cursor-pointer transition-colors duration-200 residence-label border-gray-200 hover:border-pink-300 text-center">
                                                    <div class="font-medium text-gray-800">Kontrakan</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                                    <textarea id="shipping_address" name="shipping_address" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-pink-500 focus:border-pink-500 transition-colors duration-200" placeholder="Masukkan alamat lengkap (jalan, nomor rumah, RT/RW, patokan, dll)" required>{{ $user->address ?? '' }}</textarea>
                                </div>
                            </div>
                            
                            <!-- Shipping Options -->
                            <div class="mb-6">
                                <h3 class="text-md font-semibold text-gray-800 mb-3">Pilih Kurir</h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                                    <div class="relative">
                                        <input type="radio" id="courier-jne" name="shipping_courier" value="jne" class="hidden courier-radio" checked>
                                        <label for="courier-jne" class="block p-3 border rounded-lg cursor-pointer transition-colors duration-200 courier-label border-pink-500 bg-pink-50 text-center">
                                            <div class="font-bold text-gray-800 mb-1">JNE</div>
                                            <div class="text-xs text-gray-500">Regular</div>
                                        </label>
                                    </div>
                                    
                                    <div class="relative">
                                        <input type="radio" id="courier-pos" name="shipping_courier" value="pos" class="hidden courier-radio">
                                        <label for="courier-pos" class="block p-3 border rounded-lg cursor-pointer transition-colors duration-200 courier-label border-gray-200 hover:border-pink-300 text-center">
                                            <div class="font-bold text-gray-800 mb-1">POS</div>
                                            <div class="text-xs text-gray-500">Indonesia</div>
                                        </label>
                                    </div>
                                    
                                    <div class="relative">
                                        <input type="radio" id="courier-tiki" name="shipping_courier" value="tiki" class="hidden courier-radio">
                                        <label for="courier-tiki" class="block p-3 border rounded-lg cursor-pointer transition-colors duration-200 courier-label border-gray-200 hover:border-pink-300 text-center">
                                            <div class="font-bold text-gray-800 mb-1">TIKI</div>
                                            <div class="text-xs text-gray-500">Titipan Kilat</div>
                                        </label>
                                    </div>
                                </div>
                                
                                <div id="shipping-services-container" class="mt-4 hidden">
                                    <h3 class="text-md font-semibold text-gray-800 mb-3">Pilih Layanan Pengiriman</h3>
                                    <div id="shipping-services" class="grid grid-cols-1 gap-3">
                                        <div class="bg-gray-100 rounded-lg p-4 animate-pulse flex justify-center items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            <span class="text-sm text-gray-500">Memuat opsi pengiriman...</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Hidden inputs for selected shipping -->
                                <input type="hidden" name="shipping_service" id="shipping_service" required>
                                <input type="hidden" name="shipping_cost" id="shipping_cost" required>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" id="submit-button" class="w-full px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-md hover:from-pink-600 hover:to-pink-700 shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <span>Lanjut ke Pembayaran</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Shipping Information -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Informasi Pengiriman
                        </h2>
                        
                        <div class="bg-gray-50 rounded-lg p-4">
                            <ul class="space-y-3 text-sm">
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Pastikan alamat pengiriman sudah benar dan lengkap untuk menghindari kendala pengiriman.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Biaya pengiriman dihitung berdasarkan berat total dan lokasi pengiriman.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Estimasi waktu pengiriman dapat bervariasi tergantung pada layanan kurir dan lokasi tujuan.</span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Resi pengiriman akan diberikan setelah pesanan diproses dan dikirim.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-1/3" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 sticky top-24">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Ringkasan Pesanan
                        </h2>
                        
                        <div class="border-b border-gray-200 pb-4 mb-4">
                            <div class="text-sm text-gray-600 mb-2">{{ $cartItems->count() }} item</div>
                            
                            <div class="max-h-64 overflow-y-auto pr-2 space-y-3">
                                @foreach($cartItems as $item)
                                <div class="flex items-start">
                                    <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden">
                                        @if(isset($checkoutType) && $checkoutType == 'buy_now')
                                            <!-- Untuk Buy Now Item -->
                                            @if(!empty($item['product_image']))
                                                <img src="{{ asset('storage/' . $item['product_image']) }}" alt="{{ $item['product_name'] }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        @else
                                            <!-- Untuk Cart Item Normal -->
                                            @if($item->product && $item->product->primaryImage())
                                                <img src="{{ asset('storage/' . $item->product->primaryImage()->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h4 class="text-sm font-medium text-gray-800">
                                            @if(isset($checkoutType) && $checkoutType == 'buy_now')
                                                {{ $item['product_name'] }}
                                            @else
                                                {{ $item->product->name }}
                                            @endif
                                        </h4>
                                        <div class="text-xs text-gray-500 mt-1">
                                            @if(isset($checkoutType) && $checkoutType == 'buy_now')
                                                {{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}
                                            @else
                                                {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                            @endif
                                        </div>
                                        <div class="text-sm font-medium text-pink-600 mt-1">
                                            @if(isset($checkoutType) && $checkoutType == 'buy_now')
                                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                            @else
                                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Calculate total weight for shipping -->
                        @php
                            $totalWeight = 0;
                            if(isset($checkoutType) && $checkoutType == 'buy_now') {
                                // Untuk Buy Now
                                foreach($cartItems as $item) {
                                    $productId = $item['product_id'];
                                    $product = App\Models\Product::find($productId);
                                    if($product) {
                                        $totalWeight += ($product->weight * $item['quantity']);
                                    }
                                }
                            } else {
                                // Untuk Cart Items
                                foreach($cartItems as $item) {
                                    if($item->product) {
                                        $totalWeight += ($item->product->weight * $item->quantity);
                                    }
                                }
                            }
                            
                            // Ensure minimum weight is 1 gram
                            $totalWeight = max(1, $totalWeight);
                            
                            // Format weight for display
                            $formattedWeight = $totalWeight;
                            $weightUnit = 'gram';
                            
                            // Convert to KG if more than 1000 grams
                            if ($totalWeight >= 1000) {
                                $formattedWeight = $totalWeight / 1000;
                                $weightUnit = 'kg';
                            }
                        @endphp
                        <input type="hidden" id="total-weight" value="{{ $totalWeight }}">
                        
                        <div class="space-y-2">
                            <!-- Total weight info -->
                            <div class="flex justify-between text-sm bg-gray-50 p-2 rounded-lg mb-2">
                                <span class="text-gray-600 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                    </svg>
                                    Total Berat
                                </span>
                                <span class="font-medium text-gray-800">{{ $formattedWeight }} {{ $weightUnit }}</span>
                            </div>
                            
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-800">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Biaya Pengiriman</span>
                                <span id="shipping-cost-display" class="font-medium text-gray-800">-</span>
                            </div>
                            <div class="border-t border-gray-200 my-2 pt-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-800 font-bold">Total</span>
                                    <span id="order-total" class="text-lg font-bold text-pink-600">Rp {{ number_format($cartTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
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
            once: true,
            duration: 800,
            easing: 'ease-out-cubic',
        });
        
        // Page loader
        const pageLoader = document.getElementById('page-loader');
        if (pageLoader) {
            setTimeout(() => {
                pageLoader.style.opacity = '0';
                setTimeout(() => {
                    pageLoader.style.display = 'none';
                }, 500);
            }, 800);
        }
        
        // Province and City Selection
        const provinceSelect = document.getElementById('province_id');
        const citySelect = document.getElementById('city_id');
        const provinceNameInput = document.getElementById('province_name');
        const cityNameInput = document.getElementById('city_name');
        
        if (provinceSelect) {
            provinceSelect.addEventListener('change', function() {
                const provinceId = this.value;
                const provinceName = this.options[this.selectedIndex].dataset.name;
                provinceNameInput.value = provinceName;
                
                if (provinceId) {
                    // Enable city selection and fetch cities
                    citySelect.disabled = true;
                    citySelect.innerHTML = '<option value="">Memuat kota...</option>';
                    
                    console.log(`Mengambil kota untuk provinsi ID: ${provinceId}`);
                    
                    fetch(`{{ route('buyer.shipping.cities') }}?province_id=${provinceId}`)
                        .then(response => {
                            console.log('Status respons getCities:', response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log('Respons getCities:', data);
                            
                            if (data.success) {
                                citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                                data.cities.forEach(city => {
                                    citySelect.innerHTML += `<option value="${city.city_id}" data-name="${city.city_name} (${city.type})">${city.city_name} (${city.type})</option>`;
                                });
                                citySelect.disabled = false;
                            } else {
                                citySelect.innerHTML = '<option value="">Error saat memuat kota</option>';
                                console.error('Gagal memuat kota:', data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching cities:', error);
                            citySelect.innerHTML = '<option value="">Error saat memuat kota</option>';
                        });
                } else {
                    citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                    citySelect.disabled = true;
                    cityNameInput.value = '';
                }
                
                updateShippingOptions();
            });
        }
        
        if (citySelect) {
            citySelect.addEventListener('change', function() {
                const cityOption = this.options[this.selectedIndex];
                if (cityOption && cityOption.dataset) {
                    const cityName = cityOption.dataset.name;
                    cityNameInput.value = cityName || '';
                    console.log(`Kota dipilih: ID=${this.value}, Nama=${cityName}`);
                } else {
                    cityNameInput.value = '';
                    console.log('Pemilihan kota tidak valid');
                }
                updateShippingOptions();
            });
        }
        
        // Courier selection
        const courierRadios = document.querySelectorAll('.courier-radio');
        const courierLabels = document.querySelectorAll('.courier-label');
        
        courierRadios.forEach((radio, index) => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    console.log(`Kurir dipilih: ${this.value}`);
                    
                    // Update courier styles
                    courierLabels.forEach(label => {
                        label.classList.remove('border-pink-500', 'bg-pink-50');
                        label.classList.add('border-gray-200');
                    });
                    
                    courierLabels[index].classList.add('border-pink-500', 'bg-pink-50');
                    courierLabels[index].classList.remove('border-gray-200');
                    
                    updateShippingOptions();
                }
            });
        });
        
        // Residence type selection
        const residenceRadios = document.querySelectorAll('.residence-radio');
        const residenceLabels = document.querySelectorAll('.residence-label');
        
        residenceRadios.forEach((radio, index) => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    console.log(`Jenis tempat tinggal dipilih: ${this.value}`);
                    
                    // Update residence type styles
                    residenceLabels.forEach(label => {
                        label.classList.remove('border-pink-500', 'bg-pink-50');
                        label.classList.add('border-gray-200');
                    });
                    
                    residenceLabels[index].classList.add('border-pink-500', 'bg-pink-50');
                    residenceLabels[index].classList.remove('border-gray-200');
                }
            });
        });
        
        // Function to update shipping options
        function updateShippingOptions() {
            const shippingServicesContainer = document.getElementById('shipping-services-container');
            const shippingServices = document.getElementById('shipping-services');
            const submitButton = document.getElementById('submit-button');
            const shippingServiceInput = document.getElementById('shipping_service');
            const shippingCostInput = document.getElementById('shipping_cost');
            
            // Check if we have all required information
            const hasAddress = document.getElementById('city_id').value !== '';
            
            if (!hasAddress) {
                console.log('Kota tujuan belum dipilih, melewati kalkulasi pengiriman');
                shippingServicesContainer.classList.add('hidden');
                submitButton.disabled = true;
                return;
            }
            
            // Get selected courier
            const selectedCourier = document.querySelector('.courier-radio:checked').value;
            
            // Get destination city ID
            const destinationCity = document.getElementById('city_id').value;
            
            if (!destinationCity) {
                console.log('ID kota tujuan kosong, melewati kalkulasi pengiriman');
                shippingServicesContainer.classList.add('hidden');
                submitButton.disabled = true;
                return;
            }
            
            // Get the total weight
            const totalWeight = document.getElementById('total-weight').value;
            
            // Show loading state
            shippingServicesContainer.classList.remove('hidden');
            shippingServices.innerHTML = `
                <div class="bg-gray-100 rounded-lg p-4 animate-pulse flex justify-center items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="text-sm text-gray-500">Memuat opsi pengiriman...</span>
                </div>
            `;
            
            // Reset shipping selection
            shippingServiceInput.value = '';
            shippingCostInput.value = '';
            submitButton.disabled = true;
            
            // Log request data for debugging
            console.log('Mengirim permintaan kalkulasi pengiriman dengan data:', {
                destination: destinationCity,
                weight: parseInt(totalWeight),
                courier: selectedCourier
            });
            
            // Fetch shipping costs
            fetch('{{ route('buyer.shipping.calculate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    destination: destinationCity,
                    weight: parseInt(totalWeight), // Make sure it's an integer
                    courier: selectedCourier
                })
            })
            .then(response => {
                console.log('Status respons kalkulasi pengiriman:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Respons kalkulasi pengiriman:', data);
                
                if (data.success && data.shipping_options && data.shipping_options.length > 0) {
                    // Render shipping options
                    shippingServices.innerHTML = '';
                    
                    data.shipping_options.forEach((option, index) => {
                        const cost = option.cost[0].value;
                        const etd = option.cost[0].etd;
                        const service = option.service;
                        
                        shippingServices.innerHTML += `
                            <div class="relative">
                                <input type="radio" id="shipping-${index}" name="shipping_option" value="${service}" 
                                    class="hidden shipping-radio" data-service="${service}" data-cost="${cost}">
                                <label for="shipping-${index}" class="block p-4 border border-gray-200 rounded-lg cursor-pointer transition-colors duration-200 shipping-label hover:border-pink-300 flex justify-between items-center">
                                    <div>
                                        <div class="font-medium text-gray-800 flex items-center">
                                            <span class="inline-block w-5 h-5 border border-gray-300 rounded-full mr-2 shipping-radio-indicator flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white shipping-check-icon hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </span>
                                            ${service}
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1">Estimasi: ${etd} hari</div>
                                    </div>
                                    <div class="text-pink-600 font-medium">Rp ${(cost).toLocaleString('id-ID')}</div>
                                </label>
                            </div>
                        `;
                    });
                    
                    console.log(`${data.shipping_options.length} opsi pengiriman tersedia`);
                    
                    // Add shipping selection event listeners
                    const shippingRadios = document.querySelectorAll('.shipping-radio');
                    const shippingLabels = document.querySelectorAll('.shipping-label');
                    
                    shippingRadios.forEach((radio, index) => {
                        radio.addEventListener('change', function() {
                            if (this.checked) {
                                // Update shipping styles
                                shippingLabels.forEach(label => {
                                    label.classList.remove('border-pink-500', 'bg-pink-50');
                                    const checkIcon = label.querySelector('.shipping-check-icon');
                                    if (checkIcon) checkIcon.classList.add('hidden');
                                    const radioIndicator = label.querySelector('.shipping-radio-indicator');
                                    if (radioIndicator) radioIndicator.classList.remove('bg-pink-500', 'border-pink-500');
                                });
                                
                                shippingLabels[index].classList.add('border-pink-500', 'bg-pink-50');
                                const checkIcon = shippingLabels[index].querySelector('.shipping-check-icon');
                                if (checkIcon) checkIcon.classList.remove('hidden');
                                const radioIndicator = shippingLabels[index].querySelector('.shipping-radio-indicator');
                                if (radioIndicator) {
                                    radioIndicator.classList.add('bg-pink-500', 'border-pink-500');
                                    radioIndicator.classList.remove('border-gray-300');
                                }
                                
                                // Update hidden inputs
                                const service = this.dataset.service;
                                const cost = this.dataset.cost;
                                
                                shippingServiceInput.value = service;
                                shippingCostInput.value = cost;
                                
                                console.log(`Layanan pengiriman dipilih: ${service}, biaya: ${cost}`);
                                
                                // Update display in the order summary
                                const shippingCostDisplay = document.getElementById('shipping-cost-display');
                                const orderTotal = document.getElementById('order-total');
                                const cartTotal = parseFloat('{{ $cartTotal }}');
                                
                                if (shippingCostDisplay) {
                                    shippingCostDisplay.textContent = `Rp ${parseInt(cost).toLocaleString('id-ID')}`;
                                }
                                
                                if (orderTotal) {
                                    const total = cartTotal + parseInt(cost);
                                    orderTotal.textContent = `Rp ${total.toLocaleString('id-ID')}`;
                                    console.log(`Total pesanan diperbarui: ${total}`);
                                }
                                
                                // Enable submit button
                                submitButton.disabled = false;
                            }
                        });
                    });
                    
                    // Select first shipping option by default
                    if (shippingRadios.length > 0) {
                        shippingRadios[0].checked = true;
                        // Trigger change event to update UI
                        shippingRadios[0].dispatchEvent(new Event('change'));
                        console.log('Opsi pengiriman pertama dipilih secara default');
                    }
                    
                } else {
                    // Handle the case where no shipping options are available
                    console.error('Tidak ada opsi pengiriman tersedia:', data.message || 'Kesalahan tidak diketahui');
                    shippingServices.innerHTML = `
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-red-800">${data.message || 'Tidak ada layanan pengiriman yang tersedia untuk tujuan ini.'}</p>
                                    <p class="text-xs text-red-700 mt-1">Silakan pilih kota tujuan yang berbeda atau hubungi kami untuk bantuan.</p>
                                </div>
                            </div>
                        </div>
                    `;
                    submitButton.disabled = true;
                }
            })
            .catch(error => {
                // Handle any network or parsing errors
                console.error('Error dalam kalkulasi pengiriman:', error);
                shippingServices.innerHTML = `
                    <div class="bg-red-50 p-4 rounded-lg">
                        <div class="flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-sm font-medium text-red-800">Terjadi kesalahan saat memuat opsi pengiriman: ${error.message || 'Kesalahan tidak diketahui'}. Silakan coba lagi.</p>
                        </div>
                    </div>
                `;
                submitButton.disabled = true;
            });
        }
        
        // Form validation before submission
        const shippingForm = document.getElementById('shipping-form');
        if (shippingForm) {
            shippingForm.addEventListener('submit', function(e) {
                const province = document.getElementById('province_id').value;
                const city = document.getElementById('city_id').value;
                const postalCode = document.getElementById('postal_code').value;
                const address = document.getElementById('detailed_address').value;
                const district = document.getElementById('district').value;
                const village = document.getElementById('village').value;
                const recipientName = document.getElementById('recipient_name').value;
                const recipientPhone = document.getElementById('recipient_phone').value;
                const hasShippingService = document.getElementById('shipping_service').value !== '';
                
                console.log('Validasi form pengiriman:', {
                    province, city, district, village, postalCode, address, 
                    recipientName, recipientPhone, hasShippingService
                });
                
                if (!province || !city) {
                    e.preventDefault();
                    alert('Silakan pilih Provinsi dan Kota/Kabupaten.');
                    console.error('Validasi form gagal: Provinsi atau Kota tidak dipilih');
                    return false;
                }
                
                if (!district || !village) {
                    e.preventDefault();
                    alert('Silakan lengkapi informasi Kecamatan dan Kelurahan/Desa.');
                    console.error('Validasi form gagal: Kecamatan atau Kelurahan tidak diisi');
                    return false;
                }
                
                if (!postalCode || !address) {
                    e.preventDefault();
                    alert('Silakan lengkapi alamat pengiriman.');
                    console.error('Validasi form gagal: Informasi alamat tidak lengkap');
                    return false;
                }
                
                if (!recipientName || !recipientPhone) {
                    e.preventDefault();
                    alert('Silakan lengkapi informasi penerima (Nama dan No. WhatsApp).');
                    console.error('Validasi form gagal: Informasi penerima tidak lengkap');
                    return false;
                }
                
                // Validasi nomor telepon (WhatsApp)
                const phonePattern = /^(08|\+628)[0-9]{8,11}$/;
                if (!phonePattern.test(recipientPhone)) {
                    e.preventDefault();
                    alert('Format nomor WhatsApp tidak valid. Gunakan format 08xx atau +628xx.');
                    console.error('Validasi form gagal: Format nomor WhatsApp tidak valid');
                    return false;
                }
                
                if (!hasShippingService) {
                    e.preventDefault();
                    alert('Silakan pilih layanan pengiriman.');
                    console.error('Validasi form gagal: Layanan pengiriman belum dipilih');
                    return false;
                }
                
                console.log('Validasi form berhasil, melanjutkan ke pembayaran');
                
                // Show loading animation
                submitButton.innerHTML = `
                    <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memproses...</span>
                `;
                submitButton.disabled = true;
            });
        }
        
        // Initialize shipping options if we already have an address
        if (document.getElementById('city_id').value !== '') {
            console.log('Kota sudah dipilih, menginisialisasi opsi pengiriman');
            updateShippingOptions();
        }
        
        // Format phone number as user types
        const phoneInput = document.getElementById('recipient_phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value;
                
                // Remove all non-numeric characters
                value = value.replace(/\D/g, '');
                
                // Format as Indonesian mobile number
                if (value.startsWith('62')) {
                    value = '+' + value;
                } else if (value.startsWith('0')) {
                    // Keep the leading zero
                } else if (value.length > 0) {
                    value = '0' + value;
                }
                
                // Limit length
                if (value.startsWith('+62')) {
                    value = value.slice(0, 14); // +62 + 11 digits
                } else if (value.startsWith('0')) {
                    value = value.slice(0, 13); // 0 + 12 digits
                }
                
                e.target.value = value;
            });
        }
        
        // Log debugging info
        console.log('Kota asal diatur ke ID: 152 (Jakarta Pusat)');
        console.log('Info user:', {
            province_id: '{{ $user->province_id ?? "kosong" }}',
            province: '{{ $user->province ?? "kosong" }}',
            city_id: '{{ $user->city_id ?? "kosong" }}',
            city: '{{ $user->city ?? "kosong" }}'
        });
        console.log('Total berat untuk pengiriman:', document.getElementById('total-weight')?.value || 'tidak ditemukan');
        console.log('Script pengiriman diinisialisasi dengan sukses');
    });
</script>

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
    
    /* Smooth transitions */
    .transition-all {
        transition: all 0.3s ease;
    }
    
    /* Loading animation for progress bar */
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
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
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
    
    /* Pulse animation */
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(236, 72, 153, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(236, 72, 153, 0); }
        100% { box-shadow: 0 0 0 0 rgba(236, 72, 153, 0); }
    }
    
    .animate-pulse-ring {
        animation: pulse 1.5s infinite;
    }
</style>
@endsection