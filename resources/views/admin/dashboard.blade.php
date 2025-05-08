@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Users</div>
                <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalUsers }}</div>
            </div>
            <div class="text-blue-500 dark:text-blue-300">
                <i class="fas fa-users fa-2x"></i>
            </div>
        </div>

        <!-- Total Products -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Products</div>
                <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalProducts }}</div>
            </div>
            <div class="text-green-500 dark:text-green-300">
                <i class="fas fa-box fa-2x"></i>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg flex items-center justify-between">
            <div>
                <div class="text-sm font-semibold text-gray-500 dark:text-gray-400">Total Orders</div>
                <div class="text-2xl font-bold text-gray-800 dark:text-white">{{ $totalOrders }}</div>
            </div>
            <div class="text-teal-500 dark:text-teal-300">
                <i class="fas fa-shopping-cart fa-2x"></i>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <!-- Recent Orders Table -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Orders</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-white">#{{ $order->id }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Rp
                                    {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    @if ($order->status == 'pending')
                                        <span
                                            class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-400 px-2 py-1 rounded-full text-xs">Pending</span>
                                    @elseif($order->status == 'processing')
                                        <span
                                            class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-400 px-2 py-1 rounded-full text-xs">Processing</span>
                                    @elseif($order->status == 'completed')
                                        <span
                                            class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-400 px-2 py-1 rounded-full text-xs">Completed</span>
                                    @elseif($order->status == 'cancelled')
                                        <span
                                            class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-400 px-2 py-1 rounded-full text-xs">Cancelled</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-600 dark:text-gray-400 py-4">No orders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Dark/Light Mode Toggle Script
        const themeToggleButton = document.getElementById('themeToggle');
        themeToggleButton.addEventListener('click', () => {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        });

        // Check if the user has a saved theme preference
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
@endpush
