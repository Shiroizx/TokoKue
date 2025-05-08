@extends('user.layouts.app')

@section('styles')
<!-- Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<!-- AOS - Animate On Scroll -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- Custom CSS -->
<style>
    .hover-grow {
        transition: all 0.3s ease;
    }
    .hover-grow:hover {
        transform: scale(1.05);
    }
    .profile-nav-item {
        transition: all 0.2s ease;
    }
    .profile-nav-item:hover {
        background-color: #f3f4f6;
        padding-left: 1rem;
        color: #4f46e5;
    }
    .btn-gradient {
        background-image: linear-gradient(to right, #4f46e5, #6366f1);
        transition: all 0.3s ease;
    }
    .btn-gradient:hover {
        background-image: linear-gradient(to right, #4338ca, #4f46e5);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
    }
    .form-input-focus:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
    }
    .profile-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 50%;
    }
    .profile-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(79, 70, 229, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: all 0.3s ease;
    }
    .profile-image-container:hover .profile-image-overlay {
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-7xl">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="w-full lg:w-1/4" data-aos="fade-right" data-aos-duration="1000">
                <!-- Profile Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6 hover-grow">
                    <div class="p-6 text-center">
                        <div class="profile-image-container mx-auto mb-4 w-40 h-40">
                            @php
                                $latestProfileImage = $user->latestProfileImage;
                            @endphp

                            @if($latestProfileImage)
                                <img src="{{ asset('storage/' . $latestProfileImage->image_path) }}" 
                                    alt="{{ $user->name }}" 
                                    class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('images/default-profile.png') }}" 
                                    alt="{{ $user->name }}" 
                                    class="w-full h-full object-cover">
                            @endif

                            <div class="profile-image-overlay">
                                <span class="text-white text-sm font-medium">
                                    <i class="fas fa-camera"></i> Ubah Foto
                                </span>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-1">{{ $user->name }}</h3>
                        <div class="flex items-center justify-center space-x-1 text-gray-500 mb-2">
                            <i class="fas fa-envelope text-sm"></i>
                            <p class="text-sm">{{ $user->email }}</p>
                        </div>
                        @if(isset($customer) && $customer->address)
                        <div class="flex items-center justify-center space-x-1 text-gray-500">
                            <i class="fas fa-map-marker-alt text-sm"></i>
                            <p class="text-sm">{{ $customer->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Navigation Card -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-grow">
                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800 mb-3 px-2">Menu Navigasi</h4>
                        <ul>
                            <li class="profile-nav-item rounded-lg mb-1">
                                <a href="{{ route('buyer.profile.show') }}" class="flex items-center py-3 px-2 text-gray-700">
                                    <i class="fas fa-user-circle text-indigo-600 mr-3"></i>
                                    <span>Lihat Profil</span>
                                    <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
                                </a>
                            </li>
                            <li class="profile-nav-item rounded-lg mb-1">
                                <a href="{{ route('buyer.home') }}" class="flex items-center py-3 px-2 text-gray-700">
                                    <i class="fas fa-shopping-bag text-indigo-600 mr-3"></i>
                                    <span>Kembali ke Produk</span>
                                    <i class="fas fa-chevron-right ml-auto text-gray-400"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="w-full lg:w-3/4" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="300">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="border-b border-gray-200">
                        <div class="px-6 py-5">
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-user-edit mr-3 text-indigo-600"></i>
                                Edit Profil
                            </h2>
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="bg-green-50 border-l-4 border-green-500 p-4 m-6" role="alert" id="successAlert" data-aos="fade-down">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <div class="-mx-1.5 -my-1.5">
                                        <button type="button" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none" id="closeAlert">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Personal Information -->
                                <div class="space-y-4" data-aos="fade-up" data-aos-delay="100">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fas fa-user text-indigo-600 mr-1"></i> Nama Lengkap
                                        </label>
                                        <input type="text" id="name" name="name" 
                                               class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none @error('name') border-red-500 @enderror" 
                                               value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fas fa-envelope text-indigo-600 mr-1"></i> Email
                                        </label>
                                        <input type="email" id="email" name="email" 
                                               class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none @error('email') border-red-500 @enderror" 
                                               value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                
                                <!-- Contact Information -->
                                <div class="space-y-4" data-aos="fade-up" data-aos-delay="200">
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fas fa-phone text-indigo-600 mr-1"></i> Nomor Telepon
                                        </label>
                                        <input type="text" id="phone" name="phone" 
                                            class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none @error('phone') border-red-500 @enderror" 
                                            value="{{ old('phone', $user->phone ?? '') }}">
                                        @error('phone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fas fa-map-marker-alt text-indigo-600 mr-1"></i> Alamat
                                        </label>
                                        <textarea id="address" name="address" rows="3" 
                                            class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none @error('address') border-red-500 @enderror">{{ old('address', $user->address ?? '') }}</textarea>
                                        @error('address')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="pos" class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fas fa-mail-bulk text-indigo-600 mr-1"></i> Kode Pos
                                        </label>
                                        <input type="text" id="pos" name="pos" 
                                            class="form-input-focus w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none @error('pos') border-red-500 @enderror" 
                                            value="{{ old('pos', $user->pos ?? '') }}">
                                        @error('pos')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Profile Picture -->
                            <div class="mt-6" data-aos="fade-up" data-aos-delay="300">
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">
                                    <i class="fas fa-camera text-indigo-600 mr-1"></i> Foto Profil
                                </label>
                                <div class="flex items-center space-x-4">
                                    <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-indigo-300">
                                        @if($latestProfileImage)
                                            <img src="{{ asset('storage/' . $latestProfileImage->image_path) }}" 
                                                alt="Preview" 
                                                id="imagePreview" 
                                                class="w-full h-full object-cover">
                                        @else
                                            <img src="{{ asset('images/default-profile.png') }}" 
                                                alt="Preview" 
                                                id="imagePreview" 
                                                class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="relative">
                                            <input type="file" id="image" name="image" 
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                                onchange="previewImage()">
                                            <div class="px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200 transition-colors">
                                                <i class="fas fa-upload mr-2"></i> Pilih Foto
                                            </div>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto.</p>
                                        @error('image')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Password Section -->
                            <div class="mt-8 pt-6 border-t border-gray-200" data-aos="fade-up" data-aos-delay="400">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-lock text-indigo-600 mr-2"></i>
                                    Ubah Password
                                </h3>
                                <p class="text-sm text-gray-500 mb-4">Biarkan kosong jika tidak ingin mengubah password.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fas fa-lock text-indigo-600 mr-1"></i> Password Lama
                                        </label>
                                        <div class="relative">
                                            <input type="password" id="current_password" name="current_password" 
                                                autocomplete="off"
                                                class="form-input-focus w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none @error('current_password') border-red-500 @enderror">
                                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" onclick="togglePasswordVisibility('current_password')">
                                                <i class="fas fa-eye" id="current_password-icon"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fas fa-key text-indigo-600 mr-1"></i> Password Baru
                                        </label>
                                        <div class="relative">
                                            <input type="password" id="password" name="password" 
                                                autocomplete="new-password"
                                                class="form-input-focus w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none @error('password') border-red-500 @enderror">
                                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" onclick="togglePasswordVisibility('password')">
                                                <i class="fas fa-eye" id="password-icon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                            <i class="fas fa-check-circle text-indigo-600 mr-1"></i> Konfirmasi Password Baru
                                        </label>
                                        <div class="relative">
                                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                                autocomplete="new-password"
                                                class="form-input-focus w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none">
                                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600" onclick="togglePasswordVisibility('password_confirmation')">
                                                <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="mt-8 text-right" data-aos="fade-up" data-aos-delay="500">
                                <button type="submit" class="btn-gradient px-6 py-3 text-white font-medium rounded-lg flex items-center justify-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- AOS - Animate On Scroll -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init({
            once: true
        });
        
        // Close alert button
        const closeAlertBtn = document.getElementById('closeAlert');
        const successAlert = document.getElementById('successAlert');
        
        if(closeAlertBtn && successAlert) {
            closeAlertBtn.addEventListener('click', function() {
                successAlert.style.display = 'none';
            });
            
            // Auto close alert after 5 seconds
            setTimeout(function() {
                successAlert.style.display = 'none';
            }, 5000);
        }
        
        // Dark mode toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const darkModeSwitch = document.getElementById('darkModeSwitch');
        const darkModeIndicator = document.getElementById('darkModeIndicator');
        
        if(darkModeToggle && darkModeSwitch && darkModeIndicator) {
            // Check for saved theme preference
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            
            // Apply theme preference
            if(isDarkMode) {
                document.documentElement.classList.add('dark');
                darkModeSwitch.classList.add('bg-indigo-600');
                darkModeIndicator.classList.add('translate-x-6');
            }
            
            darkModeToggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Toggle dark mode
                const isDark = document.documentElement.classList.toggle('dark');
                darkModeSwitch.classList.toggle('bg-indigo-600', isDark);
                darkModeSwitch.classList.toggle('bg-gray-300', !isDark);
                darkModeIndicator.classList.toggle('translate-x-6', isDark);
                
                // Save preference
                localStorage.setItem('darkMode', isDark);
            });
        }
    });
    
    // Image preview function
    function previewImage() {
        const input = document.getElementById('image');
        const preview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Toggle password visibility
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '-icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection

