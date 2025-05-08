@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('styles')
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .status-badge {
        @apply text-xs font-medium px-2.5 py-0.5 rounded-full transition-all duration-300;
    }
    .card {
        @apply rounded-xl shadow-md overflow-hidden border border-gray-100 bg-white dark:bg-gray-800 dark:border-gray-700 transition-all duration-300 hover:shadow-lg;
    }
    .card-header {
        @apply p-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r;
    }
    .card-body {
        @apply p-5;
    }
    .form-label {
        @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
    }
    .form-control {
        @apply w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-300;
    }
    .form-select {
        @apply w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-300;
    }
    .btn {
        @apply inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-300;
    }
    .btn-primary {
        @apply bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-500 dark:bg-indigo-700 dark:hover:bg-indigo-600;
    }
    .btn-secondary {
        @apply bg-gray-300 hover:bg-gray-400 text-gray-800 focus:ring-gray-500 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500;
    }
    .table-section {
        @apply bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden;
    }
    .timeline-item {
        @apply relative pb-10 last:pb-0;
    }
    .timeline-item:not(:last-child):before {
        content: '';
        @apply absolute left-4 top-8 h-full w-0.5 bg-gray-200 dark:bg-gray-700;
    }
</style>
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-6" data-aos="fade-down" data-aos-duration="800">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-shopping-bag text-indigo-600 mr-3"></i>
                    Order #{{ $order->id }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    <i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('d M Y, H:i') }}
                </p>
            </div>
            <a href="{{ route('admin.orders.index') }}"
                class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Back to Orders
            </a>
        </div>
    </div>

    <!-- Order Status Overview -->
    <div class="card mb-8" data-aos="fade-up" data-aos-duration="800">
        <div class="p-5">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Order Status -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Order Status:</span>
                    @if($order->status == 'pending')
                        <span class="status-badge bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            <i class="fas fa-clock mr-1"></i> Pending
                        </span>
                    @elseif($order->status == 'processing')
                        <span class="status-badge bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            <i class="fas fa-cog fa-spin mr-1"></i> Processing
                        </span>
                    @elseif($order->status == 'shipped')
                        <span class="status-badge bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                            <i class="fas fa-shipping-fast mr-1"></i> Shipped
                        </span>
                    @elseif($order->status == 'delivered')
                        <span class="status-badge bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <i class="fas fa-check-circle mr-1"></i> Delivered
                        </span>
                    @elseif($order->status == 'cancelled')
                        <span class="status-badge bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            <i class="fas fa-times-circle mr-1"></i> Cancelled
                        </span>
                    @endif
                </div>
                
                <!-- Payment Status -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Payment Status:</span>
                    @if($order->payment_status == 'paid')
                        <span class="status-badge bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <i class="fas fa-check-circle mr-1"></i> Paid
                        </span>
                    @else
                        <span class="status-badge bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            <i class="fas fa-exclamation-circle mr-1"></i> Unpaid
                        </span>
                    @endif
                </div>
                
                <!-- Payment Method -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Payment Method:</span>
                    <span class="font-medium text-gray-800 dark:text-gray-200">
                        @if($order->payment_method == 'bank_transfer')
                            <i class="fas fa-university mr-1"></i> Bank Transfer
                        @elseif($order->payment_method == 'credit_card')
                            <i class="far fa-credit-card mr-1"></i> Credit Card
                        @elseif($order->payment_method == 'e_wallet')
                            <i class="fas fa-wallet mr-1"></i> E-Wallet
                        @else
                            <i class="fas fa-money-bill-wave mr-1"></i> {{ $order->payment_method ?? 'Not specified' }}
                        @endif
                    </span>
                </div>
                
                <!-- Tracking Number -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Tracking Number:</span>
                    @if($order->tracking_number)
                        <span class="font-medium text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-barcode mr-1"></i> {{ $order->tracking_number }}
                        </span>
                    @else
                        <span class="text-sm text-gray-500 dark:text-gray-400 italic">
                            <i class="fas fa-info-circle mr-1"></i> Not assigned yet
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="relative pt-1">
                    <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200 dark:bg-gray-700">
                        @if($order->status == 'pending')
                            <div style="width: 20%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-yellow-500"></div>
                        @elseif($order->status == 'processing')
                            <div style="width: 40%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-500"></div>
                        @elseif($order->status == 'shipped')
                            <div style="width: 75%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-indigo-500"></div>
                        @elseif($order->status == 'delivered')
                            <div style="width: 100%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-green-500"></div>
                        @elseif($order->status == 'cancelled')
                            <div style="width: 100%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-red-500"></div>
                        @endif
                    </div>
                    
                    <div class="flex text-xs justify-between">
                        <span class="text-gray-600 dark:text-gray-400 {{ $order->status != 'cancelled' && $order->status != 'pending' ? 'font-semibold' : '' }}">Order Placed</span>
                        <span class="text-gray-600 dark:text-gray-400 {{ $order->status != 'cancelled' && ($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered') ? 'font-semibold' : '' }}">Processing</span>
                        <span class="text-gray-600 dark:text-gray-400 {{ $order->status != 'cancelled' && ($order->status == 'shipped' || $order->status == 'delivered') ? 'font-semibold' : '' }}">Shipped</span>
                        <span class="text-gray-600 dark:text-gray-400 {{ $order->status != 'cancelled' && $order->status == 'delivered' ? 'font-semibold' : '' }}">Delivered</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Information Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Customer and Recipient Information -->
        <div class="grid grid-cols-1 gap-6" data-aos="fade-right" data-aos-duration="900">
            <!-- Customer Information -->
            <div class="card">
                <div class="card-header from-blue-50 to-indigo-50 dark:from-blue-900 dark:to-indigo-900">
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-user-circle text-indigo-600 mr-2"></i> Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 mr-3">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Name</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $order->user->name }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 mr-3">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                                <p class="font-medium text-gray-800 dark:text-white break-all">{{ $order->user->email }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 mr-3">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Phone</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $order->user->phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recipient Information -->
            <div class="card">
                <div class="card-header from-green-50 to-teal-50 dark:from-green-900 dark:to-teal-900">
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-address-card text-teal-600 mr-2"></i> Recipient Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center text-teal-600 dark:text-teal-300 mr-3">
                                <i class="fas fa-user-tag"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Name</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $order->recipient_name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center text-teal-600 dark:text-teal-300 mr-3">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Phone</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $order->recipient_phone ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-10 h-10 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center text-teal-600 dark:text-teal-300 mr-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Shipping Address</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $order->shipping_address ?? 'N/A' }}</p>
                                
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Province</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->province_name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">City</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->city_name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">District</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->district ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Village</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->village ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center mt-2">
                                    <div class="mr-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Postal Code</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $order->postal_code ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Residence Type</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                            @if($order->residence_type == 'house')
                                                <i class="fas fa-home mr-1"></i> House
                                            @elseif($order->residence_type == 'apartment')
                                                <i class="fas fa-building mr-1"></i> Apartment
                                            @elseif($order->residence_type == 'kos')
                                                <i class="fas fa-door-open mr-1"></i> Boarding House (Kos)
                                            @elseif($order->residence_type == 'rent')
                                                <i class="fas fa-key mr-1"></i> Rental
                                            @else
                                                Not specified
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Timeline & Shipping Details -->
        <div class="grid grid-cols-1 gap-6" data-aos="fade-left" data-aos-duration="900">
            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header from-purple-50 to-fuchsia-50 dark:from-purple-900 dark:to-fuchsia-900">
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-history text-purple-600 mr-2"></i> Order Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="space-y-0">
                        <div class="timeline-item">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 z-10">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white">Order Placed</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Customer placed order #{{ $order->id }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($order->payment_status == 'paid')
                        <div class="timeline-item">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-300 z-10">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white">Payment Completed</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Payment of Rp {{ number_format($order->total_amount, 0, ',', '.') }} received via {{ $order->payment_method }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered')
                        <div class="timeline-item">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 z-10">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white">Order Processing</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->updated_at->format('d M Y, H:i') }}</p>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Your order is being prepared for shipment</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'shipped' || $order->status == 'delivered')
                        <div class="timeline-item">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center text-purple-600 dark:text-purple-300 z-10">
                                        <i class="fas fa-shipping-fast"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white">Order Shipped</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->shipped_at ? $order->shipped_at->format('d M Y, H:i') : $order->updated_at->format('d M Y, H:i') }}</p>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                        Order has been shipped via {{ $order->shipping_courier ?? 'courier' }}
                                        @if($order->tracking_number)
                                         with tracking number: <span class="font-medium text-indigo-600 dark:text-indigo-400">{{ $order->tracking_number }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'delivered')
                        <div class="timeline-item">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-300 z-10">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white">Order Delivered</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->delivered_at ? $order->delivered_at->format('d M Y, H:i') : $order->updated_at->format('d M Y, H:i') }}</p>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Order has been delivered successfully</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->status == 'cancelled')
                        <div class="timeline-item">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center text-red-600 dark:text-red-300 z-10">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white">Order Cancelled</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $order->canceled_at ? $order->canceled_at->format('d M Y, H:i') : $order->updated_at->format('d M Y, H:i') }}</p>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Order has been cancelled</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Shipping Details -->
            <div class="card">
                <div class="card-header from-blue-50 to-cyan-50 dark:from-blue-900 dark:to-cyan-900">
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-truck text-blue-600 mr-2"></i> Shipping Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 mr-3">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Shipping Courier</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $order->shipping_courier ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 mr-3">
                                <i class="fas fa-truck-loading"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Shipping Service</p>
                                <p class="font-medium text-gray-800 dark:text-white">{{ $order->shipping_service ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 mr-3">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Shipping Cost</p>
                                <p class="font-medium text-gray-800 dark:text-white">
                                    @if($order->shipping_cost)
                                        Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-300 mr-3">
                                <i class="fas fa-weight-hanging"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total Weight</p>
                                <p class="font-medium text-gray-800 dark:text-white">
                                    @if($order->total_weight)
                                        {{ number_format($order->total_weight, 2, ',', '.') }} kg
                                    @else
                                        N/A
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Notes (if any) -->
            @if($order->notes)
            <div class="card">
                <div class="card-header from-yellow-50 to-amber-50 dark:from-yellow-900 dark:to-amber-900">
                    <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                        <i class="fas fa-sticky-note text-amber-600 mr-2"></i> Order Notes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="bg-amber-50 dark:bg-amber-900/30 p-4 rounded-lg border-l-4 border-amber-500">
                        <p class="text-gray-700 dark:text-gray-300 italic">
                            <i class="fas fa-quote-left text-amber-400 mr-2 opacity-50"></i>
                            {{ $order->notes }}
                            <i class="fas fa-quote-right text-amber-400 ml-2 opacity-50"></i>
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Order Items -->
    <div class="card mb-8" data-aos="fade-up" data-aos-duration="1000">
        <div class="card-header from-pink-50 to-rose-50 dark:from-pink-900 dark:to-rose-900">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                <i class="fas fa-box-open text-pink-600 mr-2"></i> Order Items
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                    <thead class="text-xs text-gray-700 dark:text-gray-300 uppercase bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3">Product</th>
                            <th class="px-6 py-3">Price</th>
                            <th class="px-6 py-3">Quantity</th>
                            <th class="px-6 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($order->orderItems as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($item->product)
                                            @if($item->product->primaryImage())
                                                <img src="{{ asset('storage/' . $item->product->primaryImage()->image_path) }}" 
                                                    alt="{{ $item->product->name }}" class="h-14 w-14 rounded-lg object-cover border border-gray-200 dark:border-gray-700 mr-4">
                                            @elseif($item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                    alt="{{ $item->product->name }}" class="h-14 w-14 rounded-lg object-cover border border-gray-200 dark:border-gray-700 mr-4">
                                            @else
                                                <img src="{{ asset('images/default-product.png') }}" 
                                                    alt="{{ $item->product->name }}" class="h-14 w-14 rounded-lg object-cover border border-gray-200 dark:border-gray-700 mr-4">
                                            @endif
                                        @else
                                            <div class="h-14 w-14 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 mr-4">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-medium text-gray-800 dark:text-white">
                                                {{ $item->product ? $item->product->name : 'Product Removed' }}
                                            </h3>
                                            @if($item->product && isset($item->product->sku))
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    SKU: {{ $item->product->sku }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-center bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-800 font-medium text-gray-700 dark:text-gray-300">
                        <tr>
                            <th colspan="3" class="px-6 py-3 text-right">Subtotal</th>
                            <th class="px-6 py-3 text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</th>
                        </tr>
                        @if($order->shipping_cost)
                        <tr>
                            <th colspan="3" class="px-6 py-3 text-right">Shipping Cost</th>
                            <th class="px-6 py-3 text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</th>
                        </tr>
                        @endif
                        @if($order->discount)
                        <tr>
                            <th colspan="3" class="px-6 py-3 text-right">Discount</th>
                            <th class="px-6 py-3 text-right text-green-600 dark:text-green-400">-Rp {{ number_format($order->discount, 0, ',', '.') }}</th>
                        </tr>
                        @endif
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th colspan="3" class="px-6 py-3 text-right text-lg">Total</th>
                            <th class="px-6 py-3 text-right text-lg text-indigo-600 dark:text-indigo-400">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Admin Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8" data-aos="fade-up" data-aos-duration="1200">
        <!-- Update Order Status -->
        <div class="card" data-aos="zoom-in" data-aos-delay="100">
            <div class="card-header from-indigo-50 to-blue-50 dark:from-indigo-900 dark:to-blue-900">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-tasks text-indigo-600 mr-2"></i> Order Status
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="status" class="form-label">Update Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                Processing
                            </option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                Shipped
                            </option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                Delivered
                            </option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fas fa-save mr-2"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Update Payment Status -->
        <div class="card" data-aos="zoom-in" data-aos-delay="200">
            <div class="card-header from-green-50 to-emerald-50 dark:from-green-900 dark:to-emerald-900">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-money-check-alt text-green-600 mr-2"></i> Payment Status
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-payment', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="payment_status" class="form-label">Update Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="unpaid" {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>
                                Unpaid
                            </option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-full bg-green-600 hover:bg-green-700 focus:ring-green-500 dark:bg-green-700 dark:hover:bg-green-600">
                        <i class="fas fa-save mr-2"></i> Update Payment
                    </button>
                </form>
            </div>
        </div>

        <!-- Update Tracking Number -->
        <div class="card" data-aos="zoom-in" data-aos-delay="300">
            <div class="card-header from-purple-50 to-violet-50 dark:from-purple-900 dark:to-violet-900">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-truck text-purple-600 mr-2"></i> Tracking Number
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-tracking', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-4">
                        <label for="tracking_number" class="form-label">Update Tracking Number</label>
                        <input type="text" class="form-control" id="tracking_number" name="tracking_number"
                            value="{{ $order->tracking_number }}" placeholder="Enter tracking number">
                    </div>
                    <button type="submit" class="btn btn-primary w-full bg-purple-600 hover:bg-purple-700 focus:ring-purple-500 dark:bg-purple-700 dark:hover:bg-purple-600">
                        <i class="fas fa-save mr-2"></i> Update Tracking
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Shipping Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8" data-aos="fade-up" data-aos-duration="1200">
        <!-- Update Shipping Courier & Service -->
        <div class="card" data-aos="zoom-in" data-aos-delay="100">
            <div class="card-header from-cyan-50 to-blue-50 dark:from-cyan-900 dark:to-blue-900">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-truck-loading text-cyan-600 mr-2"></i> Shipping Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-shipping', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="shipping_courier" class="form-label">Shipping Courier</label>
                            <input type="text" class="form-control" id="shipping_courier" name="shipping_courier"
                                value="{{ $order->shipping_courier }}" placeholder="e.g. JNE, TIKI, etc.">
                        </div>
                        <div>
                            <label for="shipping_service" class="form-label">Shipping Service</label>
                            <input type="text" class="form-control" id="shipping_service" name="shipping_service"
                                value="{{ $order->shipping_service }}" placeholder="e.g. REG, OKE, YES, etc.">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="shipping_cost" class="form-label">Shipping Cost (Rp)</label>
                            <input type="number" class="form-control" id="shipping_cost" name="shipping_cost"
                                value="{{ $order->shipping_cost }}" placeholder="Enter shipping cost">
                        </div>
                        <div>
                            <label for="total_weight" class="form-label">Total Weight (kg)</label>
                            <input type="number" step="0.01" class="form-control" id="total_weight" name="total_weight"
                                value="{{ $order->total_weight }}" placeholder="Enter total weight">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-full bg-cyan-600 hover:bg-cyan-700 focus:ring-cyan-500 dark:bg-cyan-700 dark:hover:bg-cyan-600">
                        <i class="fas fa-save mr-2"></i> Update Shipping Info
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Timestamps -->
        <div class="card" data-aos="zoom-in" data-aos-delay="200">
            <div class="card-header from-rose-50 to-pink-50 dark:from-rose-900 dark:to-pink-900">
                <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                    <i class="fas fa-calendar-alt text-rose-600 mr-2"></i> Order Timestamps
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update-timestamps', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-1 gap-4 mb-4">
                        <div>
                            <label for="shipped_at" class="form-label">Shipped Date</label>
                            <input type="datetime-local" class="form-control" id="shipped_at" name="shipped_at"
                                value="{{ $order->shipped_at ? $order->shipped_at->format('Y-m-d\TH:i') : '' }}">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to use the time when status changes to 'shipped'</p>
                        </div>
                        <div>
                            <label for="delivered_at" class="form-label">Delivered Date</label>
                            <input type="datetime-local" class="form-control" id="delivered_at" name="delivered_at"
                                value="{{ $order->delivered_at ? $order->delivered_at->format('Y-m-d\TH:i') : '' }}">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to use the time when status changes to 'delivered'</p>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-full bg-rose-600 hover:bg-rose-700 focus:ring-rose-500 dark:bg-rose-700 dark:hover:bg-rose-600">
                        <i class="fas fa-save mr-2"></i> Update Timestamps
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card mb-8" data-aos="fade-up" data-aos-duration="1200" data-aos-delay="100">
        <div class="card-header from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
            <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                <i class="fas fa-bolt text-amber-500 mr-2"></i> Quick Actions
            </h5>
        </div>
        <div class="card-body">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-secondary flex items-center">
                    <i class="fas fa-print mr-2"></i> Print Invoice
                </a>
                
                <a href="{{ route('admin.orders.export', $order->id) }}" class="btn btn-secondary flex items-center">
                    <i class="fas fa-file-export mr-2"></i> Export Order
                </a>
                
                @if($order->status != 'cancelled')
                    <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white focus:ring-red-500 dark:bg-red-700 dark:hover:bg-red-600 flex items-center">
                            <i class="fas fa-ban mr-2"></i> Cancel Order
                        </button>
                    </form>
                @endif
                
                <a href="mailto:{{ $order->user->email }}" class="btn btn-secondary flex items-center">
                    <i class="fas fa-envelope mr-2"></i> Contact Customer
                </a>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init({
            once: true,
            duration: 800
        });
        
        // Loading progress animation
        const progressBar = document.querySelector('.loading-progress');
        if (progressBar) {
            setTimeout(() => {
                progressBar.style.width = '100%';
            }, 500);
        }
        
        // Card hover effect
        const cards = document.querySelectorAll('.card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.classList.add('transform', 'scale-[1.01]', 'z-10');
            });
            
            card.addEventListener('mouseleave', function() {
                this.classList.remove('transform', 'scale-[1.01]', 'z-10');
            });
        });
        
        // Form validation and feedback
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('border-red-500', 'focus:ring-red-500');
                        
                        // Add error message if it doesn't exist
                        const errorId = `${field.id}-error`;
                        if (!document.getElementById(errorId)) {
                            const errorMsg = document.createElement('p');
                            errorMsg.id = errorId;
                            errorMsg.className = 'text-red-500 text-xs mt-1';
                            errorMsg.textContent = 'This field is required';
                            field.parentNode.appendChild(errorMsg);
                        }
                    } else {
                        field.classList.remove('border-red-500', 'focus:ring-red-500');
                        
                        // Remove error message if it exists
                        const errorMsg = document.getElementById(`${field.id}-error`);
                        if (errorMsg) errorMsg.remove();
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    return false;
                }
                
                // Add loading state to button
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
                }
            });
        });
        
        // Theme toggle functionality
        const themeToggle = document.getElementById('theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', function() {
                document.documentElement.classList.toggle('dark');
                
                // Save preference to localStorage
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                    this.innerHTML = '<i class="fas fa-sun"></i>';
                } else {
                    localStorage.setItem('theme', 'light');
                    this.innerHTML = '<i class="fas fa-moon"></i>';
                }
            });
            
            // Set initial state based on localStorage or system preference
            if (localStorage.getItem('theme') === 'dark' || 
                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
                themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            } else {
                document.documentElement.classList.remove('dark');
                themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            }
        }
    });
</script>
@endsection
@endsection