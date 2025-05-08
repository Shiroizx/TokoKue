@extends('seller.layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex flex-col items-start mb-6">
            <a href="{{ route('seller.orders.index') }}" 
               class="flex items-center mb-4 px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg text-gray-700 dark:text-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Back to Orders
            </a>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden w-full">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                                Order #{{ $order->order_number ?? 'ORD-'.str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                            </h1>
                            <p class="text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i> {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        
                        <div class="mt-4 md:mt-0">
                            @if ($order->status == 'pending')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-500">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                            @elseif($order->status == 'processing')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500">
                                    <i class="fas fa-cog fa-spin mr-1"></i> Processing
                                </span>
                            @elseif($order->status == 'shipped')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-500">
                                    <i class="fas fa-shipping-fast mr-1"></i> Shipped
                                </span>
                            @elseif($order->status == 'delivered')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-500">
                                    <i class="fas fa-check-circle mr-1"></i> Delivered
                                </span>
                            @elseif($order->status == 'cancelled')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500">
                                    <i class="fas fa-times-circle mr-1"></i> Canceled
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Customer Information -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                                <i class="fas fa-user mr-2 text-primary-600 dark:text-primary-400"></i> Customer Information
                            </h2>
                            <div class="space-y-2">
                                <p class="text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Name:</span> {{ $order->user->name }}
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Email:</span> {{ $order->user->email }}
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Phone:</span> {{ $order->user->phone ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Shipping Information -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                                <i class="fas fa-shipping-fast mr-2 text-primary-600 dark:text-primary-400"></i> Shipping Information
                            </h2>
                            <div class="space-y-2">
                                <p class="text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Address:</span> {{ $order->shipping_address ?? 'N/A' }}
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">City & Province:</span> 
                                    {{ $order->city_name ?? 'N/A' }}, {{ $order->province_name ?? 'N/A' }}
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Kecamatan:</span> {{ $order->district ?? 'N/A' }}
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Kelurahan/Desa:</span> {{ $order->village ?? 'N/A' }}
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Postal Code:</span> {{ $order->postal_code ?? 'N/A' }}
                                </p>
                                <p class="text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Contact Phone:</span> {{ $order->recipient_phone ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Status Timeline -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                            <i class="fas fa-history mr-2 text-primary-600 dark:text-primary-400"></i> Order Status
                        </h2>
                        
                        <!-- Progress bar -->
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mb-4">
                            @if ($order->status == 'pending')
                                <div class="bg-yellow-500 h-2.5 rounded-full" style="width: 20%"></div>
                            @elseif($order->status == 'processing')
                                <div class="bg-blue-500 h-2.5 rounded-full" style="width: 40%"></div>
                            @elseif($order->status == 'shipped')
                                <div class="bg-indigo-500 h-2.5 rounded-full" style="width: 70%"></div>
                            @elseif($order->status == 'delivered')
                                <div class="bg-green-500 h-2.5 rounded-full" style="width: 100%"></div>
                            @elseif($order->status == 'cancelled')
                                <div class="bg-red-500 h-2.5 rounded-full" style="width: 100%"></div>
                            @endif
                        </div>
                        
                        <!-- Status labels -->
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400">
                            <span class="{{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'font-semibold' : '' }}">Pending</span>
                            <span class="{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'font-semibold' : '' }}">Processing</span>
                            <span class="{{ in_array($order->status, ['shipped', 'delivered']) ? 'font-semibold' : '' }}">Shipped</span>
                            <span class="{{ $order->status == 'delivered' ? 'font-semibold' : '' }}">Delivered</span>
                        </div>
                        
                        <!-- Update status form -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="flex">
                                    <select name="status" class="flex-grow rounded-l-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-r-lg transition-colors">
                                        Update Status
                                    </button>
                                </div>
                                @error('status')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </form>
                            
                            <form action="{{ route('seller.orders.updateTracking', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="flex">
                                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" 
                                           placeholder="Enter tracking number" 
                                           class="flex-grow rounded-l-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50">
                                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-r-lg transition-colors">
                                        Update Tracking
                                    </button>
                                </div>
                                @error('tracking_number')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </form>
                        </div>
                        
                        <!-- Status timestamps -->
                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-xs">
                                <i class="far fa-clock mr-1"></i> Created: {{ $order->created_at->format('d M Y, H:i') }}
                            </span>
                            
                            @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered')
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded text-xs">
                                    <i class="fas fa-cog mr-1"></i> Processing: {{ $order->updated_at->format('d M Y, H:i') }}
                                </span>
                            @endif

                            @if($order->status == 'shipped' || $order->status == 'delivered')
                                <span class="px-2 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400 rounded text-xs">
                                    <i class="fas fa-shipping-fast mr-1"></i> Shipped: {{ $order->shipped_at ? $order->shipped_at->format('d M Y, H:i') : $order->updated_at->format('d M Y, H:i') }}
                                </span>
                            @endif
                            
                            @if($order->status == 'delivered')
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded text-xs">
                                    <i class="fas fa-check-circle mr-1"></i> Delivered: {{ $order->delivered_at ? $order->delivered_at->format('d M Y, H:i') : $order->updated_at->format('d M Y, H:i') }}
                                </span>
                            @endif
                            
                            @if($order->status == 'canceled')
                                <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded text-xs">
                                    <i class="fas fa-times-circle mr-1"></i> Canceled: {{ $order->canceled_at ? $order->canceled_at->format('d M Y, H:i') : $order->updated_at->format('d M Y, H:i') }}
                                </span>
                            @endif
                            
                            @if($order->tracking_number)
                                <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 rounded text-xs">
                                    <i class="fas fa-truck mr-1"></i> Tracking: {{ $order->tracking_number }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Order Items -->
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                            <i class="fas fa-shopping-basket mr-2 text-primary-600 dark:text-primary-400"></i> Your Products in This Order
                        </h2>
                        
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-100 dark:bg-gray-600">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Product
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Price
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Quantity
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Subtotal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($order->items as $item)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        @if($item->product && $item->product->primaryImage())
                                                            <img src="{{ asset('storage/' . $item->product->primaryImage()->image_path) }}" 
                                                                alt="{{ $item->product_name }}" 
                                                                class="h-12 w-12 object-cover rounded border border-gray-200 dark:border-gray-700">
                                                        @else
                                                            <div class="h-12 w-12 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                                                <i class="fas fa-image text-gray-400 dark:text-gray-500"></i>
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                                {{ $item->product_name }}
                                                            </div>
                                                            @if($item->product && $item->product->sku)
                                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                    SKU: {{ $item->product->sku }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900 dark:text-white">
                                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                    <span class="bg-gray-100 dark:bg-gray-700 rounded-full px-3 py-1">
                                                        {{ $item->quantity }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Your Items Subtotal:
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    Rp {{ number_format($sellerSubtotal, 0, ',', '.') }}
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Shipping Cost:
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') ?? '0' }}
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="bg-gray-100 dark:bg-gray-600">
                                            <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Total:
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    Rp {{ number_format($sellerSubtotal + ($order->shipping_cost ?? 0), 0, ',', '.') }}
                                                </div>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mt-4 bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <p class="text-blue-800 dark:text-blue-300 text-sm">
                                <i class="fas fa-info-circle mr-2"></i> 
                                <strong>Note:</strong> This order may contain products from other sellers. You are only seeing your products in this order.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Order Notes -->
                    @if($order->notes)
                        <div class="mb-6">
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                                <i class="fas fa-sticky-note mr-2 text-primary-600 dark:text-primary-400"></i> Customer Notes
                            </h2>
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border-l-4 border-yellow-400 dark:border-yellow-600">
                                <p class="text-gray-700 dark:text-gray-300 italic">
                                    {{ $order->notes }}
                                </p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 mt-6">
                        @if($order->status != 'delivered' && $order->status != 'canceled')
                            <a href="mailto:{{ $order->user->email }}" 
                                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                                <i class="fas fa-envelope mr-2"></i> Contact Customer
                            </a>
                            
                            <button type="button" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors" 
                                onclick="printOrderDetails()">
                                <i class="fas fa-print mr-2"></i> Print Details
                            </button>
                            
                            @if($order->status == 'pending')
                                <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                        <i class="fas fa-times-circle mr-2"></i> Cancel Order
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function printOrderDetails() {
            window.print();
        }
    </script>
@endsection