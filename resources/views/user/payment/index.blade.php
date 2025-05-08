@extends('user.layouts.app')

@section('title', 'Pembayaran Pesanan')

@section('content')
<!-- Page Loading Animation -->
<div id="page-loader" class="fixed inset-0 bg-white z-50 flex items-center justify-center transition-opacity duration-500">
    <div class="flex flex-col items-center">
        <div class="relative">
            <svg class="animate-spin h-16 w-16 text-pink-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div class="absolute inset-0 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
        </div>
        <p class="mt-4 text-gray-600 font-medium">Memuat Halaman Pembayaran...</p>
        <div class="mt-3 w-48 h-1 bg-gray-200 rounded-full overflow-hidden">
            <div class="loading-progress h-full bg-gradient-to-r from-pink-400 to-pink-600 rounded-full"></div>
        </div>
    </div>
</div>

<!-- Payment Section -->
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col-reverse lg:flex-row gap-8">
            <!-- Payment Method Section -->
            <div class="w-full lg:w-2/3" data-aos="fade-up" data-aos-duration="800">
                <!-- Produk Section -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 mb-6">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Detail Produk
                        </h2>
                        
                        <div class="space-y-4">
                            @foreach($orderItems as $item)
                            <div class="flex items-center border-b pb-4 last:border-b-0">
                                <div class="w-20 h-20 flex-shrink-0 mr-4">
                                    <img 
                                        src="{{ $item['image'] }}" 
                                        alt="{{ $item['product']->name }}" 
                                        class="w-full h-full object-cover rounded-lg"
                                    >
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-md font-semibold text-gray-800">{{ $item['product']->name }}</h3>
                                    <div class="text-sm text-gray-600 mt-1">
                                        <span>{{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-sm text-pink-600 mt-1">
                                        Subtotal: Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Berat: {{ $item['weight'] }} gram
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Metode Pembayaran Section -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 mb-6">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            Pilih Metode Pembayaran
                        </h2>
                        
                        <!-- Pembayaran Detail -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="text-gray-700 font-medium">Metode Pembayaran Midtrans</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Anda akan diarahkan ke halaman pembayaran Midtrans untuk menyelesaikan transaksi.
                                </p>
                            </div>
                        </div>

                        <!-- Payment Button -->
                        <div class="mt-6">
                            <button id="pay-button" class="w-full px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-md hover:from-pink-600 hover:to-pink-700 shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center">
                                <span>Bayar Sekarang</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-1/3" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-100 sticky top-24">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Ringkasan Pesanan
                        </h2>
                        
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Jumlah Produk</span>
                                <span class="font-medium text-gray-800">{{ $totalProducts }} item</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Berat</span>
                                <span class="font-medium text-gray-800">
                                    {{ $totalWeight >= 1000 ? number_format($totalWeight / 1000, 2) . ' kg' : $totalWeight . ' gram' }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Total Pesanan</span>
                                <span class="font-medium text-gray-800">Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Biaya Pengiriman</span>
                                <span class="font-medium text-gray-800">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-gray-200 my-2 pt-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-800 font-bold">Total</span>
                                    <span class="text-lg font-bold text-pink-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Midtrans Snap.js -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Page loader
        const pageLoader = document.getElementById('page-loader');
        if (pageLoader) {
            setTimeout(() => {
                pageLoader.style.opacity = '0';
                setTimeout(() => {
                    pageLoader.style.display = 'none';
                }, 500);
            }, 800);
        }

        // Update script di payment.blade.php
        document.getElementById('pay-button').addEventListener('click', function() {
            // Trigger Midtrans payment
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    
                    // Kirim request AJAX untuk update stock
                    updateStockAfterPayment(result);
                    
                    // Redirect dengan parameter tambahan
                    const redirectUrl = '{{ route("buyer.orders.show", $order->id) }}' + 
                        '?payment_status=success' + 
                        '&payment_method=' + encodeURIComponent(result.payment_type) +
                        '&transaction_id=' + encodeURIComponent(result.transaction_id);
                    
                    window.location.href = redirectUrl;
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    window.location.href = '{{ route("buyer.payment.unfinish") }}';
                },
                onError: function(result) {
                    console.error('Payment error:', result);
                    window.location.href = '{{ route("buyer.payment.error") }}';
                },
                onClose: function() {
                    console.log('Payment window closed');
                }
            });
        });
    });

    // Fungsi untuk update stock setelah pembayaran
    async function updateStockAfterPayment(paymentResult) {
        // Gunakan FormData untuk mengirim data
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('order_id', '{{ $order->id }}');
        formData.append('payment_result', JSON.stringify(paymentResult));

        const response = await fetch('{{ route("buyer.payment.update-stock") }}', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            console.log('Stock updated successfully');
        } else {
            console.error('Failed to update stock:', result.message);
        }
    }
</script>
@endsection