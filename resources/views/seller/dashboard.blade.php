@extends('seller.layouts.app')

@section('title', 'Seller Dashboard')

@section('content')
    <!-- Dashboard Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 md:mb-0">Dashboard</h1>
        <a href="{{ route('seller.products.create') }}"
            class="flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <i class="fas fa-plus mr-2"></i> Add New Product
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div
            class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-lg shadow-md overflow-hidden dashboard-card">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-white text-opacity-80 text-sm font-medium">Total Products</p>
                        <div class="flex items-baseline mt-1">
                            <p class="text-3xl font-semibold text-white">{{ $totalProducts }}</p>
                            <span class="ml-2 text-sm font-medium text-white text-opacity-70">items</span>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-box text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('seller.products.index') }}"
                        class="text-sm font-medium text-white text-opacity-80 hover:text-opacity-100">
                        View all products <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Active Products -->
        <div
            class="bg-gradient-to-r from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 rounded-lg shadow-md overflow-hidden dashboard-card">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-white text-opacity-80 text-sm font-medium">Active Products</p>
                        <div class="flex items-baseline mt-1">
                            <p class="text-3xl font-semibold text-white">{{ $activeProducts }}</p>
                            <span class="ml-2 text-sm font-medium text-white text-opacity-70">items</span>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-check-circle text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="pt-1">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-white bg-white bg-opacity-20 px-2 py-1 rounded-full">
                                {{ $totalProducts > 0 ? round(($activeProducts / $totalProducts) * 100) : 0 }}% active
                            </span>
                        </div>
                        <div class="mt-2 h-2 bg-white bg-opacity-20 rounded overflow-hidden">
                            <div style="width: {{ $totalProducts > 0 ? ($activeProducts / $totalProducts) * 100 : 0 }}%"
                                class="h-full bg-white"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div
            class="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-lg shadow-md overflow-hidden dashboard-card">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-white text-opacity-80 text-sm font-medium">Total Orders</p>
                        <div class="flex items-baseline mt-1">
                            <p class="text-3xl font-semibold text-white">{{ $totalOrders }}</p>
                            <span class="ml-2 text-sm font-medium text-white text-opacity-70">orders</span>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-shopping-cart text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('seller.orders.index') }}"
                        class="text-sm font-medium text-white text-opacity-80 hover:text-opacity-100">
                        View all orders <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Pending Orders -->
        <div
            class="bg-gradient-to-r from-amber-500 to-amber-600 dark:from-amber-600 dark:to-amber-700 rounded-lg shadow-md overflow-hidden dashboard-card">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-white text-opacity-80 text-sm font-medium">Pending Orders</p>
                        <div class="flex items-baseline mt-1">
                            <p class="text-3xl font-semibold text-white">{{ $pendingOrders }}</p>
                            <span class="ml-2 text-sm font-medium text-white text-opacity-70">orders</span>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-clock text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="pt-1">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-white bg-white bg-opacity-20 px-2 py-1 rounded-full">
                                {{ $totalOrders > 0 ? round(($pendingOrders / $totalOrders) * 100) : 0 }}% pending
                            </span>
                        </div>
                        <div class="mt-2 h-2 bg-white bg-opacity-20 rounded overflow-hidden">
                            <div style="width: {{ $totalOrders > 0 ? ($pendingOrders / $totalOrders) * 100 : 0 }}%"
                                class="h-full bg-white"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Container -->
    <div id="dashboard-charts-container" class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8"></div>

    <!-- Data Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-7 gap-6 mb-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 h-full">
                <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-4 flex justify-between items-center">
                    <h2 class="font-medium text-lg text-gray-800 dark:text-white">
                        <i class="fas fa-shopping-cart mr-2 text-primary-600 dark:text-primary-400"></i>Recent Orders
                    </h2>
                    <a href="{{ route('seller.orders.index') }}"
                        class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
                        View All
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Order #</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Customer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('seller.orders.show', $order) }}"
                                            class="text-primary-600 dark:text-primary-400 font-medium hover:underline">
                                            {{ $order->order_number ?? $order->id }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ optional($order->user)->name ?? 'Customer' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($order->status == 'pending')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500">Pending</span>
                                        @elseif($order->status == 'processing')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500">Processing</span>
                                        @elseif($order->status == 'shipped')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-500">Shipped</span>
                                        @elseif($order->status == 'delivered')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500">Delivered</span>
                                        @elseif($order->status == 'canceled')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500">Canceled</span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-500">{{ $order->status ?? 'Unknown' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-12 w-12 text-gray-300 dark:text-gray-500 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                            <p>No orders found</p>
                                            <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">New orders will
                                                appear
                                                here</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 h-full">
                <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-4 flex justify-between items-center">
                    <h2 class="font-medium text-lg text-gray-800 dark:text-white">
                        <i class="fas fa-exclamation-triangle mr-2 text-amber-500"></i>Low Stock Products
                    </h2>
                    <a href="{{ route('seller.products.index') }}"
                        class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
                        View All
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Product</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Stock</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($lowStockProducts as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('seller.products.edit', $product) }}"
                                            class="text-gray-900 dark:text-white font-medium hover:text-primary-600 dark:hover:text-primary-400">
                                            {{ $product->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($product->stock <= 5)
                                            <span
                                                class="text-red-600 dark:text-red-400 font-bold">{{ $product->stock }}</span>
                                        @else
                                            <span class="text-amber-600 dark:text-amber-400">{{ $product->stock }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('seller.products.edit', $product) }}"
                                            class="text-primary-600 bg-primary-50 dark:bg-primary-900/30 dark:text-primary-400 hover:bg-primary-100 dark:hover:bg-primary-900/50 px-3 py-1.5 rounded-lg text-sm font-medium inline-flex items-center">
                                            <i
                                                class="fas fa-pen-to-square mr-1.5 text-primary-500 dark:text-primary-400"></i>
                                            Update
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="h-12 w-12 text-gray-300 dark:text-gray-500 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                                </path>
                                            </svg>
                                            <p>No low stock products found</p>
                                            <p class="mt-1 text-sm text-gray-400 dark:text-gray-500">All your products
                                                have
                                                sufficient stock</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tandai halaman ini sebagai halaman dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Tandai halaman ini sebagai halaman dashboard
            document.body.classList.add('dashboard-page');

            // Get chart data from PHP variables
            const salesData = @json($salesData);
            const salesLabels = @json($salesLabels);
            const productData = @json($productCategories);
            
            // Buat grafik penjualan
            const chartsContainer = document.getElementById('dashboard-charts-container');
            if (chartsContainer) {
                // Buat Sales Chart
                const salesChartDiv = document.createElement('div');
                salesChartDiv.className =
                    'bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-5 dashboard-card';
                salesChartDiv.innerHTML = `
                <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Sales Overview</h3>
                <div class="h-64">
                    <canvas id="sales-chart"></canvas>
                </div>
            `;
                chartsContainer.appendChild(salesChartDiv);

                // Buat Products Chart
                const productsChartDiv = document.createElement('div');
                productsChartDiv.className =
                    'bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-5 dashboard-card';
                productsChartDiv.innerHTML = `
                <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-4">Product Categories</h3>
                <div class="h-64">
                    <canvas id="products-chart"></canvas>
                </div>
            `;
                chartsContainer.appendChild(productsChartDiv);

                // Inisialisasi grafik
                const isDark = document.documentElement.classList.contains('dark');

                // Sales Chart
                const salesCtx = document.getElementById('sales-chart').getContext('2d');
                window.salesChart = new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: salesLabels,
                        datasets: [{
                            label: 'Monthly Sales',
                            data: salesData,
                            backgroundColor: 'rgba(14, 165, 233, 0.2)',
                            borderColor: 'rgba(14, 165, 233, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            pointBackgroundColor: 'rgba(14, 165, 233, 1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    color: isDark ? 'rgba(71, 85, 105, 0.3)' : 'rgba(226, 232, 240, 0.7)'
                                },
                                ticks: {
                                    color: isDark ? '#94a3b8' : '#64748b'
                                }
                            },
                            y: {
                                grid: {
                                    color: isDark ? 'rgba(71, 85, 105, 0.3)' : 'rgba(226, 232, 240, 0.7)'
                                },
                                ticks: {
                                    color: isDark ? '#94a3b8' : '#64748b',
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: isDark ? '#e2e8f0' : '#1e293b'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Rp ' + context.raw.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });

                // Products Chart
                const productsCtx = document.getElementById('products-chart').getContext('2d');
                window.productsChart = new Chart(productsCtx, {
                    type: 'doughnut',
                    data: {
                        labels: productData.map(item => item.name),
                        datasets: [{
                            data: productData.map(item => item.value),
                            backgroundColor: [
                                'rgba(14, 165, 233, 0.7)', // blue
                                'rgba(249, 115, 22, 0.7)', // orange
                                'rgba(16, 185, 129, 0.7)', // green
                                'rgba(139, 92, 246, 0.7)', // purple
                                'rgba(239, 68, 68, 0.7)' // red
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: isDark ? '#e2e8f0' : '#1e293b',
                                    padding: 20
                                }
                            }
                        }
                    }
                });
            }
            
            // PENTING: Reinisialisasi dropdown pada halaman dashboard
            const profileMenuButton = document.getElementById('profile-menu-button');
            const profileDropdown = document.getElementById('profile-dropdown');
            
            if (profileMenuButton && profileDropdown) {
                // Pastikan dropdown disembunyikan pada awalnya
                profileDropdown.classList.add('hidden');
                
                // Tambahkan event listener untuk toggle dropdown
                profileMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation(); // Mencegah event dari bubbling ke document
                    profileDropdown.classList.toggle('hidden');
                });
                
                // Tutup dropdown saat mengklik di luar
                document.addEventListener('click', function(e) {
                    if (!profileMenuButton.contains(e.target) && !profileDropdown.contains(e.target)) {
                        profileDropdown.classList.add('hidden');
                    }
                });
            }
        });
    </script>
@endpush
