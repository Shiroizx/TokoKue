@extends('admin.layouts.app')

@section('title', 'Manage Orders')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <h6 class="text-lg font-semibold text-gray-800 dark:text-white">Orders</h6>
        </div>

        <!-- Orders Table -->
        <div
            class="overflow-x-auto bg-white dark:bg-gray-700 shadow-md rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Order ID</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Customer</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Total Amount</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Payment Status</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Date</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">#{{ $order->id }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">Rp
                                {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm">
                                @switch($order->status)
                                    @case('pending')
                                        <span class="badge bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 py-1 px-2.5 rounded-full text-xs font-medium">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                        @break
                                    @case('processing')
                                        <span class="badge bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 py-1 px-2.5 rounded-full text-xs font-medium">
                                            <i class="fas fa-cog fa-spin mr-1"></i>Processing
                                        </span>
                                        @break
                                    @case('shipped')
                                        <span class="badge bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300 py-1 px-2.5 rounded-full text-xs font-medium">
                                            <i class="fas fa-shipping-fast mr-1"></i>Shipped
                                        </span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 py-1 px-2.5 rounded-full text-xs font-medium">
                                            <i class="fas fa-check-circle mr-1"></i>Delivered
                                        </span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 py-1 px-2.5 rounded-full text-xs font-medium">
                                            <i class="fas fa-times-circle mr-1"></i>Cancelled
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 py-1 px-2.5 rounded-full text-xs font-medium">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($order->payment_status == 'paid')
                                    <span class="badge bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 py-1 px-2.5 rounded-full text-xs font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>Paid
                                    </span>
                                @else
                                    <span class="badge bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 py-1 px-2.5 rounded-full text-xs font-medium">
                                        <i class="fas fa-exclamation-circle mr-1"></i>Unpaid
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <i class="fas fa-shopping-cart text-4xl text-gray-400 dark:text-gray-600"></i>
                                    <p>No orders found</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex justify-end">
            {{ $orders->links('pagination::tailwind') }}
        </div>
    </div>
@endsection