<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Toko Kue Kelompok 2</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Gradient background */
            background: linear-gradient(135deg, #4158D0, #C850C0, #FFCC70);
            background-size: 300% 300%;
            animation: gradient-animation 15s ease infinite;
            position: relative;
        }

        /* Animated gradient background */
        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Blur overlay */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            backdrop-filter: blur(8px);
            z-index: -1;
        }

        .login-card {
            display: flex;
            max-width: 900px;
            width: 90%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 10;
        }

        .login-form {
            background-color: #0f172a;
            color: white;
            width: 50%;
            padding: 40px;
        }

        .brand-content {
            background-color: #2563eb;
            color: white;
            width: 50%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Animated pattern for brand content */
        .brand-content::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            right: -50%;
            bottom: -50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 6px);
            background-size: 20px 20px;
            opacity: 0.3;
            z-index: 0;
            animation: pattern-move 120s linear infinite;
        }

        @keyframes pattern-move {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(50%, 50%);
            }
        }

        .brand-content * {
            position: relative;
            z-index: 1;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
        }

        .divider-line {
            flex-grow: 1;
            height: 1px;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .divider-text {
            padding: 0 20px;
            color: rgba(255, 255, 255, 0.6);
        }

        .auth-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .auth-btn:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border-radius: 6px;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
            margin-top: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        /* Input hover effect */
        .form-input:hover {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
            background-color: rgba(255, 255, 255, 0.1);
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        /* Password toggle icon */
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 38px;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: white;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 14px;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            background-color: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Button effect on hover */
        .submit-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%);
            transform-origin: 50% 50%;
        }

        .submit-btn:hover::after {
            animation: ripple 1s ease-out;
        }

        @keyframes ripple {
            0% {
                transform: scale(0, 0);
                opacity: 0.5;
            }
            100% {
                transform: scale(100, 100);
                opacity: 0;
            }
        }

        .checkbox-container {
            display: flex;
            align-items: center;
        }

        .checkbox {
            margin-right: 8px;
            accent-color: #2563eb;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .user-avatars {
            display: flex;
            margin-bottom: 1rem;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #2563eb;
            margin-right: -10px;
            background-color: #fff;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .user-avatar:hover {
            transform: scale(1.1) translateY(-5px);
            z-index: 2;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Floating animation for logo */
        .logo-float {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                width: 95%;
            }

            .login-form, .brand-content {
                width: 100%;
                padding: 25px;
            }

            .login-form {
                order: 2;
            }

            .brand-content {
                order: 1;
                padding-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="login-card" data-aos="zoom-in" data-aos-duration="800">
        <!-- Login Form Side -->
        <div class="login-form" data-aos="fade-right" data-aos-delay="300">
            <h2 class="text-xl font-bold mb-6">Welcome back</h2>

            <!-- Social Login Buttons -->
            <div class="grid grid-cols-2 gap-3" data-aos="fade-up" data-aos-delay="400">
                <button class="auth-btn" onclick="window.location.href='{{ route('login.google') }}'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 48 48" class="mr-2">
                        <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                        <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                        <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                        <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                    </svg>
                    Sign in with Google
                </button>
                <button class="auth-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 384 512" class="mr-2">
                        <path fill="white" d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"/>
                    </svg>
                    Sign in with Apple
                </button>
            </div>

            <!-- Divider -->
            <div class="divider" data-aos="fade-up" data-aos-delay="500">
                <div class="divider-line"></div>
                <div class="divider-text">or</div>
                <div class="divider-line"></div>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" data-aos="fade-up" data-aos-delay="600">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Enter your email" required>
                    @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                    <span class="password-toggle" id="togglePassword">
                        <i class="far fa-eye"></i>
                    </span>
                    @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between items-center mb-5 text-sm" data-aos="fade-up" data-aos-delay="700">
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-blue-400 hover:underline">
                        Forgot password?
                    </a>
                    @endif
                </div>

                <button type="submit" class="submit-btn" data-aos="fade-up" data-aos-delay="800">
                    Sign in to your account
                </button>

                <p class="mt-5 text-center text-sm" data-aos="fade-up" data-aos-delay="900">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-blue-400 hover:underline">Sign up</a>
                </p>
            </form>
        </div>

        <!-- Brand Content Side -->
        <div class="brand-content" data-aos="fade-left" data-aos-delay="300">
            <div class="mb-4 logo-float">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="20" cy="20" r="20" fill="white"/>
                    <path d="M28 20C28 24.4183 24.4183 28 20 28C15.5817 28 12 24.4183 12 20C12 15.5817 15.5817 12 20 12C24.4183 12 28 15.5817 28 20Z" fill="#2563EB"/>
                    <path d="M22 20C22 21.1046 21.1046 22 20 22C18.8954 22 18 21.1046 18 20C18 18.8954 18.8954 18 20 18C21.1046 18 22 18.8954 22 20Z" fill="white"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold mb-3" data-aos="fade-up" data-aos-delay="400">Toko Kue Kelompok 2</h1>
            <p class="mb-6 text-sm" data-aos="fade-up" data-aos-delay="500">
                Masuk ke akun Anda untuk melanjutkan petualangan rasa manis Anda bersama kue-kue istimewa kami. Dapatkan akses cepat ke varian favorit Anda dan temukan rekomendasi personal hanya untuk Anda.
            </p>

            <div class="user-avatars" data-aos="fade-up" data-aos-delay="600">
                <div class="user-avatar">
                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User">
                </div>
                <div class="user-avatar">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User">
                </div>
                <div class="user-avatar">
                    <img src="https://randomuser.me/api/portraits/men/46.jpg" alt="User">
                </div>
                <div class="user-avatar">
                    <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="User">
                </div>
            </div>
            <p class="text-sm" data-aos="fade-up" data-aos-delay="700"><span class="font-bold">Over 15.7k</span> Happy Customers</p>
        </div>
    </div>

    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animations
            AOS.init({
                once: true,
                easing: 'ease-out-cubic',
                duration: 800
            });
            
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if(togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    // Toggle type attribute
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Toggle icon
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }
            
            // Add input animations
            const inputs = document.querySelectorAll('.form-input');
            
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('input-focused');
                });
                
                input.addEventListener('blur', function() {
                    if(this.value === '') {
                        this.parentElement.classList.remove('input-focused');
                    }
                });
            });
        });
    </script>
</body>

</html>