@extends('seller.layouts.app')

@section('title', 'Sales Analytics')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-4 md:mb-0">Sales Analytics</h1>
        
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 w-full md:w-auto">
            <form id="analytics-filter-form" action="{{ route('seller.analytics') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label for="period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Time Interval</label>
                    <select id="period" name="period" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50">
                        <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
                
                <div class="flex-1">
                    <label for="dateRange" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Range</label>
                    <select id="dateRange" name="dateRange" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50">
                        <option value="last7days" {{ $dateRange == 'last7days' ? 'selected' : '' }}>Last 7 Days</option>
                        <option value="last30days" {{ $dateRange == 'last30days' ? 'selected' : '' }}>Last 30 Days</option>
                        <option value="last3months" {{ $dateRange == 'last3months' ? 'selected' : '' }}>Last 3 Months</option>
                        <option value="last6months" {{ $dateRange == 'last6months' ? 'selected' : '' }}>Last 6 Months</option>
                        <option value="lastyear" {{ $dateRange == 'lastyear' ? 'selected' : '' }}>Last Year</option>
                        <option value="custom" {{ $dateRange == 'custom' ? 'selected' : '' }}>Custom Range</option>
                    </select>
                </div>
                
                <div id="custom-date-container" class="flex-1 {{ $dateRange == 'custom' ? 'flex' : 'hidden' }} flex-col md:flex-row gap-2">
                    <div class="flex-1">
                        <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                        <input type="date" id="startDate" name="startDate" value="{{ optional($startDate)->format('Y-m-d') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50">
                    </div>
                    
                    <div class="flex-1">
                        <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                        <input type="date" id="endDate" name="endDate" value="{{ optional($endDate)->format('Y-m-d') }}" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50">
                    </div>
                </div>
                
                <div class="flex-none self-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 active:bg-primary-800 focus:outline-none focus:border-primary-800 focus:ring focus:ring-primary-500 focus:ring-opacity-50 transition ease-in-out duration-150">
                        <i class="fas fa-filter mr-2"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Revenue Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Revenue -->
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-lg overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-white text-opacity-80 text-sm font-medium">Total Revenue</p>
                        <p class="text-3xl font-bold text-white mt-1">Rp {{ number_format($revenueData['totalRevenue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-money-bill-wave text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    @if($revenueData['previousPeriodComparison'] > 0)
                        <p class="flex items-center text-sm font-medium text-green-300">
                            <i class="fas fa-arrow-up mr-1"></i> {{ number_format(abs($revenueData['previousPeriodComparison']), 2) }}% from previous period
                        </p>
                    @elseif($revenueData['previousPeriodComparison'] < 0)
                        <p class="flex items-center text-sm font-medium text-red-300">
                            <i class="fas fa-arrow-down mr-1"></i> {{ number_format(abs($revenueData['previousPeriodComparison']), 2) }}% from previous period
                        </p>
                    @else
                        <p class="flex items-center text-sm font-medium text-white text-opacity-80">
                            No change from previous period
                        </p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Average Order Value -->
        <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg shadow-lg overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-white text-opacity-80 text-sm font-medium">Average Order Value</p>
                        <p class="text-3xl font-bold text-white mt-1">Rp {{ number_format($revenueData['averageOrderValue'], 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-shopping-cart text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-medium text-white text-opacity-80">
                        Based on {{ count($orderCountData) > 0 ? array_sum($orderCountData) : 0 }} orders
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg shadow-lg overflow-hidden">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-white text-opacity-80 text-sm font-medium">Total Orders</p>
                        <p class="text-3xl font-bold text-white mt-1">{{ count($orderCountData) > 0 ? array_sum($orderCountData) : 0 }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-full">
                        <i class="fas fa-file-invoice text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-medium text-white text-opacity-80">
                        {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Sales Overview Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-5">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-chart-line text-primary-600 dark:text-primary-400 mr-2"></i>Sales Overview
            </h2>
            <div class="h-80">
                <canvas id="sales-overview-chart"></canvas>
            </div>
        </div>
        
        <!-- Orders Count Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 p-5">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">
                <i class="fas fa-shopping-cart text-primary-600 dark:text-primary-400 mr-2"></i>Order Count
            </h2>
            <div class="h-80">
                <canvas id="orders-count-chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Category & Products Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top Selling Products -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-4">
                <h2 class="font-semibold text-lg text-gray-800 dark:text-white">
                    <i class="fas fa-star text-amber-500 mr-2"></i>Top Selling Products
                </h2>
            </div>
            <div class="p-5">
                @forelse($topSellingProducts as $product)
                    <div class="flex items-center mb-4 pb-4 {{ !$loop->last ? 'border-b border-gray-200 dark:border-gray-700' : '' }}">
                        <div class="flex-shrink-0 w-16 h-16 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-700">
                            @php
                                // Get the primary image of the product
                                $primaryImage = $product->productImages()->where('is_primary', true)->first();
                            @endphp

                            @if ($primaryImage)
                                <img src="{{ asset('storage/' . $primaryImage->image_path) }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-box text-gray-400 dark:text-gray-500 text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-base font-medium text-gray-800 dark:text-white">{{ $product->name }}</h3>
                            <div class="flex justify-between mt-1">
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <p>{{ $product->total_quantity }} units sold</p>
                                    <p class="font-medium text-primary-600 dark:text-primary-400">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <p>Unit Price:</p>
                                    <p>Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-8">
                        <i class="fas fa-box-open text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400">No product data available for the selected period</p>
                    </div>
                @endforelse
            </div>
        </div>
        
        <!-- Sales by Category -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
            <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-4">
                <h2 class="font-semibold text-lg text-gray-800 dark:text-white">
                    <i class="fas fa-chart-pie text-primary-600 dark:text-primary-400 mr-2"></i>Sales by Category
                </h2>
            </div>
            <div class="p-5">
                <div class="h-64 mb-4">
                    <canvas id="category-chart"></canvas>
                </div>
                
                <div class="mt-4">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Category
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Revenue
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Units Sold
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($salesByCategory as $category)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">
                                        {{ $category->name }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        Rp {{ number_format($category->total_revenue, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $category->total_quantity }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No category data available for the selected period
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Weight Data Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 mb-8">
        <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-4">
            <h2 class="font-semibold text-lg text-gray-800 dark:text-white">
                <i class="fas fa-weight text-yellow-500 mr-2"></i>Total Weight Over Time
            </h2>
        </div>
        <div class="p-5">
            <div class="h-80">
                <canvas id="weight-chart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Customer Locations -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 mb-8">
        <div class="border-b border-gray-200 dark:border-gray-700 px-5 py-4">
            <h2 class="font-semibold text-lg text-gray-800 dark:text-white">
                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>Customer Locations
            </h2>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="h-80">
                    <canvas id="locations-chart"></canvas>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Province
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Orders
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Revenue
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($customerLocationData as $location)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">
                                        {{ $location->province_name }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $location->order_count }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        Rp {{ number_format($location->total_revenue, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No location data available for the selected period
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
        document.addEventListener('DOMContentLoaded', function() {
            // Show/hide custom date inputs based on selection
            const dateRangeSelect = document.getElementById('dateRange');
            const customDateContainer = document.getElementById('custom-date-container');
            
            dateRangeSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    customDateContainer.classList.remove('hidden');
                    customDateContainer.classList.add('flex');
                } else {
                    customDateContainer.classList.remove('flex');
                    customDateContainer.classList.add('hidden');
                }
            });

            // Initialize Charts
            const isDark = document.documentElement.classList.contains('dark');
            const chartTextColor = isDark ? '#e2e8f0' : '#1e293b';
            const gridColor = isDark ? 'rgba(71, 85, 105, 0.3)' : 'rgba(226, 232, 240, 0.7)';
            
            // Sales Overview Chart
            const salesLabels = @json($salesOverviewLabels);
            const salesData = @json($salesOverviewData);
            
            if (document.getElementById('sales-overview-chart')) {
                const salesCtx = document.getElementById('sales-overview-chart').getContext('2d');
                const salesChart = new Chart(salesCtx, {
                    type: 'line',
                    data: {
                        labels: salesLabels,
                        datasets: [{
                            label: 'Sales',
                            data: salesData,
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: chartTextColor
                                }
                            },
                            y: {
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: chartTextColor,
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: chartTextColor
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
            }
            
            // Orders Count Chart
            const orderCountData = @json($orderCountData);
            
            if (document.getElementById('orders-count-chart')) {
                const ordersCtx = document.getElementById('orders-count-chart').getContext('2d');
                const ordersChart = new Chart(ordersCtx, {
                    type: 'bar',
                    data: {
                        labels: salesLabels,
                        datasets: [{
                            label: 'Orders',
                            data: orderCountData,
                            backgroundColor: 'rgba(139, 92, 246, 0.7)',
                            borderColor: 'rgba(139, 92, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: chartTextColor
                                }
                            },
                            y: {
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: chartTextColor,
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: chartTextColor
                                }
                            }
                        }
                    }
                });
            }
            
            // Category Chart
            const categoryData = @json($salesByCategory);
            
            if (document.getElementById('category-chart') && categoryData.length > 0) {
                const categoryLabels = categoryData.map(item => item.name);
                const categoryValues = categoryData.map(item => item.total_revenue);
                const categoryColors = [
                    'rgba(59, 130, 246, 0.7)',  // blue
                    'rgba(16, 185, 129, 0.7)',  // green
                    'rgba(249, 115, 22, 0.7)',  // orange
                    'rgba(236, 72, 153, 0.7)',  // pink
                    'rgba(139, 92, 246, 0.7)',  // purple
                    'rgba(245, 158, 11, 0.7)',  // amber
                    'rgba(6, 182, 212, 0.7)',   // cyan
                    'rgba(220, 38, 38, 0.7)',   // red
                    'rgba(132, 204, 22, 0.7)',  // lime
                    'rgba(20, 184, 166, 0.7)'   // teal
                ];
                
                const categoryCtx = document.getElementById('category-chart').getContext('2d');
                const categoryChart = new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: categoryLabels,
                        datasets: [{
                            data: categoryValues,
                            backgroundColor: categoryColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    color: chartTextColor,
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.raw;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return context.label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            // Customer Location Chart
            const locationData = @json($customerLocationData);
            
            if (document.getElementById('locations-chart') && locationData.length > 0) {
                const locationLabels = locationData.map(item => item.province_name);
                const locationValues = locationData.map(item => item.total_revenue);
                const locationColors = [
                    'rgba(59, 130, 246, 0.7)',  // blue
                    'rgba(16, 185, 129, 0.7)',  // green
                    'rgba(249, 115, 22, 0.7)',  // orange
                    'rgba(236, 72, 153, 0.7)',  // pink
                    'rgba(139, 92, 246, 0.7)',  // purple
                    'rgba(245, 158, 11, 0.7)',  // amber
                    'rgba(6, 182, 212, 0.7)',   // cyan
                    'rgba(220, 38, 38, 0.7)',   // red
                    'rgba(132, 204, 22, 0.7)',  // lime
                    'rgba(20, 184, 166, 0.7)'   // teal
                ];
                
                const locationCtx = document.getElementById('locations-chart').getContext('2d');
                const locationChart = new Chart(locationCtx, {
                    type: 'bar',
                    data: {
                        labels: locationLabels,
                        datasets: [{
                            label: 'Revenue by Location',
                            data: locationValues,
                            backgroundColor: locationColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: chartTextColor,
                                    callback: function(value) {
                                        if (value >= 1000000) {
                                            return 'Rp ' + (value / 1000000).toFixed(1) + ' Juta';
                                        } else if (value >= 1000) {
                                            return 'Rp ' + (value / 1000).toFixed(1) + ' Ribu';
                                        }
                                        return 'Rp ' + value;
                                    }
                                }
                            },
                            y: {
                                grid: {
                                    display: false,
                                    drawBorder: false
                                },
                                ticks: {
                                    color: chartTextColor
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
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
            }

            // Weight Chart
            const weightLabels = @json($weightOverviewLabels);
            const weightData = @json($weightOverviewData);

            if (document.getElementById('weight-chart')) {
                const weightCtx = document.getElementById('weight-chart').getContext('2d');
                const weightChart = new Chart(weightCtx, {
                    type: 'line',
                    data: {
                        labels: weightLabels,
                        datasets: [{
                            label: 'Total Weight (grams)',
                            data: weightData,
                            backgroundColor: 'rgba(234, 179, 8, 0.2)',
                            borderColor: 'rgba(234, 179, 8, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            pointBackgroundColor: 'rgba(234, 179, 8, 1)',
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: chartTextColor
                                }
                            },
                            y: {
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: chartTextColor,
                                    callback: function(value) {
                                        if (value >= 1000) {
                                            return (value / 1000).toFixed(1) + ' kg';
                                        }
                                        return value + ' g';
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: chartTextColor
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.raw;
                                        if (value >= 1000) {
                                            return 'Weight: ' + (value / 1000).toFixed(2) + ' kg';
                                        }
                                        return 'Weight: ' + value + ' g';
                                    }
                                }
                            }
                        }
                    }
                });
            }
            
            // Dark mode toggle support
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('change', function() {
                    // Reload the page to reinitialize charts with correct colors
                    window.location.reload();
                });
            }
        });
    </script>
@endpush