@extends('seller.layouts.app')

@section('styles')
<!-- Tailwind CSS -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<!-- AOS Animation Library -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
<!-- Feather Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.css" rel="stylesheet">
<!-- Custom Styles -->
<style>
    .form-card {
        transition: all 0.3s ease;
    }
    .form-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .input-field {
        transition: border-color 0.2s ease;
    }
    .input-field:focus {
        border-color: #3B82F6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
    }
    .btn-primary {
        background-image: linear-gradient(to right, #3B82F6, #2563EB);
    }
    .btn-primary:hover {
        background-image: linear-gradient(to right, #2563EB, #1D4ED8);
    }
    .btn-secondary {
        background-image: linear-gradient(to right, #6B7280, #4B5563);
    }
    .btn-secondary:hover {
        background-image: linear-gradient(to right, #4B5563, #374151);
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-center">
        <div class="w-full md:w-2/3 lg:w-1/2">
            <!-- Main Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 form-card" data-aos="fade-up">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center">
                        <i data-feather="settings" class="text-white mr-3"></i>
                        <h2 class="text-xl font-bold text-white">Pengaturan Akun</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert" data-aos="fade-right">
                            <div class="flex">
                                <i data-feather="check-circle" class="mr-2"></i>
                                <p>{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Tabs Navigation -->
                    <div class="flex border-b mb-6" id="tabs">
                        <button class="px-6 py-3 font-medium text-sm rounded-t-lg bg-blue-500 text-white active-tab" id="password-tab">
                            <i data-feather="lock" class="inline-block mr-2 h-4 w-4"></i>Ubah Password
                        </button>
                        <button class="px-6 py-3 font-medium text-sm text-gray-600 hover:text-blue-500" id="email-tab">
                            <i data-feather="mail" class="inline-block mr-2 h-4 w-4"></i>Ubah Email
                        </button>
                    </div>

                    <!-- Password Change Section -->
                    <div id="password-section" class="tab-content" data-aos="fade-up" data-aos-delay="100">
                        <form method="POST" action="{{ route('seller.profile.reset-password') }}" class="password-form">
                            @csrf
                            
                            <div class="mb-5">
                                <label for="current_password" class="block text-gray-700 text-sm font-medium mb-2">
                                    <i data-feather="key" class="inline-block mr-2 h-4 w-4"></i>Password Saat Ini
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                        class="w-full px-4 py-3 border rounded-lg input-field bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('current_password') border-red-500 @enderror" 
                                        id="current_password" name="current_password" placeholder="Masukkan password saat ini">
                                    <button type="button" class="toggle-password absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                        <i data-feather="eye" class="h-5 w-5"></i>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-5">
                                <label for="password" class="block text-gray-700 text-sm font-medium mb-2">
                                    <i data-feather="lock" class="inline-block mr-2 h-4 w-4"></i>Password Baru
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                        class="w-full px-4 py-3 border rounded-lg input-field bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" 
                                        id="password" name="password" placeholder="Masukkan password baru">
                                    <button type="button" class="toggle-password absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                        <i data-feather="eye" class="h-5 w-5"></i>
                                    </button>
                                </div>
                                <div class="mt-2">
                                    <div class="password-strength-meter bg-gray-200 h-1 w-full rounded-full mt-1">
                                        <div class="password-strength-value h-1 rounded-full bg-gray-500 w-0"></div>
                                    </div>
                                    <p class="password-strength-text text-xs text-gray-500 mt-1">Kekuatan password: -</p>
                                </div>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-6">
                                <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">
                                    <i data-feather="check-circle" class="inline-block mr-2 h-4 w-4"></i>Konfirmasi Password Baru
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                        class="w-full px-4 py-3 border rounded-lg input-field bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password_confirmation') border-red-500 @enderror" 
                                        id="password_confirmation" name="password_confirmation" placeholder="Masukkan kembali password baru">
                                    <button type="button" class="toggle-password absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                        <i data-feather="eye" class="h-5 w-5"></i>
                                    </button>
                                </div>
                                <p class="match-status text-xs mt-1 hidden"></p>
                                @error('password_confirmation')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition duration-200 flex items-center justify-center btn-primary">
                                <i data-feather="save" class="mr-2 h-4 w-4"></i>
                                Simpan Password Baru
                            </button>
                        </form>
                    </div>

                    <!-- Email Change Section -->
                    <div id="email-section" class="tab-content hidden" data-aos="fade-up" data-aos-delay="100">
                        <form method="POST" action="{{ route('seller.profile.change-email') }}">
                            @csrf
                            
                            <div class="mb-5">
                                <label for="email" class="block text-gray-700 text-sm font-medium mb-2">
                                    <i data-feather="at-sign" class="inline-block mr-2 h-4 w-4"></i>Email Baru
                                </label>
                                <input type="email" 
                                    class="w-full px-4 py-3 border rounded-lg input-field bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" 
                                    id="email" name="email" value="{{ old('email', Auth::user()->email) }}" placeholder="contoh@email.com">
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="mb-6">
                                <label for="email_password" class="block text-gray-700 text-sm font-medium mb-2">
                                    <i data-feather="shield" class="inline-block mr-2 h-4 w-4"></i>Konfirmasi Password
                                </label>
                                <div class="relative">
                                    <input type="password" 
                                        class="w-full px-4 py-3 border rounded-lg input-field bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror" 
                                        id="email_password" name="password" placeholder="Masukkan password Anda">
                                    <button type="button" class="toggle-password absolute right-3 top-3 text-gray-400 hover:text-gray-600">
                                        <i data-feather="eye" class="h-5 w-5"></i>
                                    </button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    <i data-feather="info" class="inline-block mr-1 h-3 w-3"></i>
                                    Masukkan password Anda untuk mengkonfirmasi perubahan email.
                                </p>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition duration-200 flex items-center justify-center btn-primary">
                                <i data-feather="save" class="mr-2 h-4 w-4"></i>
                                Perbarui Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Back Button -->
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <a href="{{ route('seller.dashboard') }}" class="inline-flex items-center justify-center w-full py-3 px-4 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg shadow transition duration-200 btn-secondary">
                    <i data-feather="arrow-left" class="mr-2 h-4 w-4"></i>
                    Kembali ke Profil
                </a>
            </div>
        </div>
    </div>
</div>
<!-- AOS Animation Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<!-- Feather Icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js"></script>
<!-- Custom Scripts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            once: true
        });
        
        // Initialize Feather icons
        feather.replace();
        
        // Tab switching functionality
        const passwordTab = document.getElementById('password-tab');
        const emailTab = document.getElementById('email-tab');
        const passwordSection = document.getElementById('password-section');
        const emailSection = document.getElementById('email-section');
        
        passwordTab.addEventListener('click', function() {
            passwordTab.classList.add('bg-blue-500', 'text-white');
            passwordTab.classList.remove('text-gray-600');
            emailTab.classList.remove('bg-blue-500', 'text-white');
            emailTab.classList.add('text-gray-600');
            
            passwordSection.classList.remove('hidden');
            emailSection.classList.add('hidden');
        });
        
        emailTab.addEventListener('click', function() {
            emailTab.classList.add('bg-blue-500', 'text-white');
            emailTab.classList.remove('text-gray-600');
            passwordTab.classList.remove('bg-blue-500', 'text-white');
            passwordTab.classList.add('text-gray-600');
            
            emailSection.classList.remove('hidden');
            passwordSection.classList.add('hidden');
        });
        
        // Toggle password visibility
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('svg');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.innerHTML = feather.icons['eye-off'].toSvg();
                } else {
                    input.type = 'password';
                    icon.innerHTML = feather.icons['eye'].toSvg();
                }
            });
        });
        
        // Password strength meter
        const passwordInput = document.getElementById('password');
        const strengthMeter = document.querySelector('.password-strength-value');
        const strengthText = document.querySelector('.password-strength-text');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let message = 'Lemah';
            let color = 'bg-red-500';
            
            // Check password length
            if (password.length >= 8) strength += 1;
            
            // Check for mixed case
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
            
            // Check for numbers
            if (password.match(/\d/)) strength += 1;
            
            // Check for special characters
            if (password.match(/[^a-zA-Z\d]/)) strength += 1;
            
            // Update strength meter
            switch (strength) {
                case 0:
                    strengthMeter.style.width = '0%';
                    strengthText.textContent = 'Kekuatan password: -';
                    strengthMeter.className = 'password-strength-value h-1 rounded-full bg-gray-500 w-0';
                    break;
                case 1:
                    strengthMeter.style.width = '25%';
                    strengthText.textContent = 'Kekuatan password: Sangat Lemah';
                    strengthMeter.className = 'password-strength-value h-1 rounded-full bg-red-500';
                    break;
                case 2:
                    strengthMeter.style.width = '50%';
                    strengthText.textContent = 'Kekuatan password: Lemah';
                    strengthMeter.className = 'password-strength-value h-1 rounded-full bg-yellow-500';
                    break;
                case 3:
                    strengthMeter.style.width = '75%';
                    strengthText.textContent = 'Kekuatan password: Sedang';
                    strengthMeter.className = 'password-strength-value h-1 rounded-full bg-blue-500';
                    break;
                case 4:
                    strengthMeter.style.width = '100%';
                    strengthText.textContent = 'Kekuatan password: Kuat';
                    strengthMeter.className = 'password-strength-value h-1 rounded-full bg-green-500';
                    break;
            }
        });
        
        // Check if passwords match
        const confirmPassword = document.getElementById('password_confirmation');
        const matchStatus = document.querySelector('.match-status');
        
        confirmPassword.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirmation = this.value;
            
            if (confirmation === '') {
                matchStatus.classList.add('hidden');
            } else if (password === confirmation) {
                matchStatus.textContent = 'Password cocok!';
                matchStatus.classList.remove('hidden', 'text-red-500');
                matchStatus.classList.add('text-green-500');
            } else {
                matchStatus.textContent = 'Password tidak cocok!';
                matchStatus.classList.remove('hidden', 'text-green-500');
                matchStatus.classList.add('text-red-500');
            }
        });
        
        // Form submission feedback
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');
                submitButton.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
                submitButton.disabled = true;
                // Form submission continues naturally
            });
        });
    });
</script>
@endsection

