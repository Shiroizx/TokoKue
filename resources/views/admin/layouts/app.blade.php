<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Office Supplies E-commerce</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">

    <!-- Custom Tailwind Configuration -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1'
                        },
                        secondary: {
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a'
                        }
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <style>
        /* Simplified styles */
        @media (min-width: 768px) {
            .sidebar {
                width: 16rem;
            }

            .main-content {
                margin-left: 16rem;
                width: calc(100% - 16rem);
            }
        }

        /* Mobile sidebar */
        @media (max-width: 767.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 16rem;
                z-index: 50;
            }

            .sidebar-open {
                transform: translateX(0);
            }

            .main-content {
                width: 100%;
            }
        }

        /* Toggle switch for dark mode */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider {
            background-color: #0ea5e9;
        }

        input:checked+.toggle-slider:before {
            transform: translateX(24px);
        }

        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            padding: 2px 6px;
            border-radius: 50%;
            background-color: #ef4444;
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* Dashboard card hover effect */
        .dashboard-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>

    @stack('styles')
</head>

<body
    class="bg-gray-50 font-sans transition-colors duration-300 dark:bg-gray-900 dark:text-white min-h-screen flex flex-col">
    <!-- Backdrop for mobile menu -->
    <div id="sidebar-backdrop" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-40 hidden md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
        class="sidebar fixed inset-y-0 left-0 bg-secondary-800 text-white dark:bg-secondary-900 overflow-y-auto overflow-x-hidden">
        <div class="flex flex-col h-full">
            <!-- Logo and header -->
            <div class="flex items-center justify-between p-4">
                <div class="flex items-center space-x-3">
                    <img src="https://placehold.co/40x40" alt="Logo" class="h-10 w-10 rounded-full">
                    <h2 class="text-lg font-bold">Admin Panel</h2>
                </div>
                <button id="sidebar-close" class="p-2 rounded hover:bg-secondary-700 md:hidden">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <hr class="border-secondary-700">

            <!-- Navigation -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary-700 text-white' : 'text-secondary-300 hover:bg-secondary-700 hover:text-white' }}">
                            <i class="fas fa-tachometer-alt w-5 flex-shrink-0"></i>
                            <span class="ml-3 truncate">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-primary-700 text-white' : 'text-secondary-300 hover:bg-secondary-700 hover:text-white' }}">
                            <i class="fas fa-users w-5 flex-shrink-0"></i>
                            <span class="ml-3 truncate">Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.categories.index') }}"
                            class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-primary-700 text-white' : 'text-secondary-300 hover:bg-secondary-700 hover:text-white' }}">
                            <i class="fas fa-tags w-5 flex-shrink-0"></i>
                            <span class="ml-3 truncate">Categories</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.products.index') }}"
                            class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-primary-700 text-white' : 'text-secondary-300 hover:bg-secondary-700 hover:text-white' }}">
                            <i class="fas fa-box w-5 flex-shrink-0"></i>
                            <span class="ml-3 truncate">Products</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.orders.index') }}"
                            class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.orders.*') ? 'bg-primary-700 text-white' : 'text-secondary-300 hover:bg-secondary-700 hover:text-white' }}">
                            <i class="fas fa-shopping-cart w-5 flex-shrink-0"></i>
                            <span class="ml-3 truncate">Orders</span>
                            <span class="notification-badge ml-2 flex-shrink-0">2</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center p-3 rounded-lg {{ request()->routeIs('admin.settings') ? 'bg-primary-700 text-white' : 'text-secondary-300 hover:bg-secondary-700 hover:text-white' }}">
                            <i class="fas fa-cog w-5 flex-shrink-0"></i>
                            <span class="ml-3 truncate">Settings</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Footer -->
            <div class="border-t border-secondary-700 p-4 w-full">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <img src="https://placehold.co/40x40" alt="Profile" class="h-8 w-8 rounded-full flex-shrink-0">
                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold truncate max-w-[90px]">{{ Auth::user()->name }}</h4>
                            <p class="text-xs text-secondary-400">Admin</p>
                        </div>
                    </div>
                    <!-- Dark Mode Toggle -->
                    <label class="toggle-switch flex-shrink-0">
                        <input type="checkbox" id="dark-mode-toggle">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center p-2 rounded bg-secondary-700 hover:bg-secondary-600 text-white">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span class="ml-2">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main id="main-content" class="main-content flex flex-col flex-grow transition-all duration-300">
        <!-- Top Navigation -->
        <header class="bg-white dark:bg-secondary-800 shadow sticky top-0 z-10">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    <!-- Menu toggle for mobile -->
                    <div class="flex items-center">
                        <button id="mobile-menu-button"
                            class="p-2 rounded-md text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700 md:hidden">
                            <i class="fas fa-bars"></i>
                        </button>

                        <div class="ml-2 text-xl font-semibold text-primary-600 dark:text-primary-500 truncate">
                            Admin Dashboard
                        </div>
                    </div>

                    <!-- Right side of navbar -->
                    <div class="flex items-center">
                        <!-- Notifications -->
                        <div class="relative mr-4">
                            <button
                                class="relative p-2 text-secondary-600 dark:text-secondary-300 hover:bg-gray-100 dark:hover:bg-secondary-700 rounded-full">
                                <i class="fas fa-bell"></i>
                                <span class="notification-badge">3</span>
                            </button>
                        </div>

                        <!-- User dropdown -->
                        <div class="relative">
                            <button id="profile-menu-button"
                                class="flex items-center text-secondary-600 dark:text-secondary-300 hover:bg-gray-100 dark:hover:bg-secondary-700 rounded-full p-1">
                                <img src="https://placehold.co/40x40" alt="Profile" class="h-8 w-8 rounded-full">
                                <span class="hidden md:block ml-2 mr-1">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs hidden md:block"></i>
                            </button>

                            <div id="profile-dropdown"
                                class="absolute right-0 mt-2 hidden w-48 bg-white dark:bg-secondary-800 rounded-md shadow-lg z-20">
                                <a href="#"
                                    class="block px-4 py-2 text-secondary-700 dark:text-secondary-200 hover:bg-gray-100 dark:hover:bg-secondary-700">
                                    Your Profile
                                </a>
                                <a href="#"
                                    class="block px-4 py-2 text-secondary-700 dark:text-secondary-200 hover:bg-gray-100 dark:hover:bg-secondary-700">
                                    Settings
                                </a>
                                <hr class="my-1 border-secondary-200 dark:border-secondary-700">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-secondary-700 dark:text-secondary-200 hover:bg-gray-100 dark:hover:bg-secondary-700">
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <div class="container mx-auto px-4 py-6 flex-grow">
            <!-- Flash Messages -->
            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 dark:bg-green-900 dark:border-green-300 dark:text-green-200">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 dark:bg-red-900 dark:border-red-300 dark:text-red-200">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Page Header -->
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-2xl font-bold leading-tight">@yield('title')</h2>
                @hasSection('header-actions')
                    <div class="mt-4 sm:mt-0">
                        @yield('header-actions')
                    </div>
                @endif
            </div>

            <!-- Page content -->
            @yield('content')
        </div>

        <!-- Footer -->
        <footer
            class="bg-white dark:bg-secondary-800 border-t border-secondary-200 dark:border-secondary-700 py-4 mt-auto">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <p class="text-sm text-secondary-600 dark:text-secondary-400 mb-4 md:mb-0">
                        © {{ date('Y') }} Office Supplies E-commerce. All rights reserved.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#"
                            class="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-500">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="#"
                            class="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-500">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#"
                            class="text-secondary-600 dark:text-secondary-400 hover:text-primary-600 dark:hover:text-primary-500">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const sidebar = document.getElementById('sidebar');
            const sidebarBackdrop = document.getElementById('sidebar-backdrop');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const sidebarClose = document.getElementById('sidebar-close');
            const mainContent = document.getElementById('main-content');
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const profileMenuButton = document.getElementById('profile-menu-button');
            const profileDropdown = document.getElementById('profile-dropdown');

            // Mobile menu toggle
            mobileMenuButton.addEventListener('click', () => {
                sidebar.classList.add('sidebar-open');
                sidebarBackdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden'); // Prevent scrolling
            });

            // Close sidebar
            function closeSidebar() {
                sidebar.classList.remove('sidebar-open');
                sidebarBackdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            // Close sidebar when clicking outside or on close button
            sidebarBackdrop.addEventListener('click', closeSidebar);
            sidebarClose.addEventListener('click', closeSidebar);

            // Close sidebar when clicking menu items on mobile
            const menuItems = document.querySelectorAll('#sidebar a');
            menuItems.forEach(item => {
                item.addEventListener('click', () => {
                    if (window.innerWidth < 768) {
                        closeSidebar();
                    }
                });
            });

            // Dark mode functionality
            function setDarkMode(isDark) {
                if (isDark) {
                    document.documentElement.classList.add('dark');
                    darkModeToggle.checked = true;
                } else {
                    document.documentElement.classList.remove('dark');
                    darkModeToggle.checked = false;
                }
            }

            // Check for saved theme or use system preference
            if (localStorage.getItem('color-theme') === 'dark' ||
                (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)
            ) {
                setDarkMode(true);
            }

            // Handle dark mode toggle
            darkModeToggle.addEventListener('change', () => {
                if (darkModeToggle.checked) {
                    localStorage.setItem('color-theme', 'dark');
                    setDarkMode(true);
                } else {
                    localStorage.setItem('color-theme', 'light');
                    setDarkMode(false);
                }
            });

            // Profile dropdown toggle
            profileMenuButton.addEventListener('click', () => {
                profileDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!profileMenuButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>
