@extends('user.layouts.app')

@section('title', 'Detail Pesanan #' . $order->id)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('buyer.orders.history') }}" class="text-pink-600 hover:text-pink-800 mr-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan #{{ $order->id }}</h1>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- Order Status Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-lg font-medium text-gray-900">Status Pesanan</h2>
                <p class="text-sm text-gray-500">Tanggal Pemesanan: {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div>
                @if($order->status == 'processing')
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Diproses
                    </span>
                @elseif($order->status == 'pending' || $order->status == 'pending_payment')
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                        Menunggu Pembayaran
                    </span>
                @elseif($order->status == 'shipped')
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        Dikirim
                    </span>
                @elseif($order->status == 'delivered')
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                        Diterima
                    </span>
                @elseif($order->status == 'cancelled')
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                        Dibatalkan
                    </span>
                @else
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                        {{ ucfirst($order->status) }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Order Timeline -->
        @if($order->status != 'cancelled')
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-between">
                    <div class="flex flex-col items-center">
                        <div class="{{ in_array($order->status, ['pending', 'pending_payment', 'processing', 'shipped', 'delivered']) ? 'bg-pink-600' : 'bg-gray-300' }} rounded-full h-5 w-5"></div>
                        <span class="text-xs mt-1 text-center {{ in_array($order->status, ['pending', 'pending_payment', 'processing', 'shipped', 'delivered']) ? 'text-pink-600 font-medium' : 'text-gray-500' }}">Pemesanan</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="{{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-pink-600' : 'bg-gray-300' }} rounded-full h-5 w-5"></div>
                        <span class="text-xs mt-1 text-center {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'text-pink-600 font-medium' : 'text-gray-500' }}">Diproses</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="{{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-pink-600' : 'bg-gray-300' }} rounded-full h-5 w-5"></div>
                        <span class="text-xs mt-1 text-center {{ in_array($order->status, ['shipped', 'delivered']) ? 'text-pink-600 font-medium' : 'text-gray-500' }}">Dikirim</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="{{ $order->status == 'delivered' ? 'bg-pink-600' : 'bg-gray-300' }} rounded-full h-5 w-5"></div>
                        <span class="text-xs mt-1 text-center {{ $order->status == 'delivered' ? 'text-pink-600 font-medium' : 'text-gray-500' }}">Diterima</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-wrap gap-2">
            @if($order->status == 'pending' || $order->status == 'pending_payment')
                <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700">
                    Bayar Sekarang
                </a>
                <form action="" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                        Batalkan
                    </button>
                </form>
            @elseif($order->status == 'shipped')
                <form action="" method="POST" class="inline">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        Konfirmasi Penerimaan
                    </button>
                </form>
            @elseif($order->status == 'delivered')
                @if(!$order->is_reviewed)
                <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700">
                    Beri Ulasan
                </a>
                @endif
            @endif
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
        <div class="border-b border-gray-200 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-900">Produk Dipesan</h2>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($order->orderItems as $item)
            <div class="flex items-center p-6">
                <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-md overflow-hidden">
                    @if($item->product)
                        @if($item->product->primaryImage())
                            <img src="{{ asset('storage/' . $item->product->primaryImage()->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @elseif($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('images/default-product.png') }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                        @endif
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="ml-6 flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-medium text-gray-900">
                                <a href="{{ route('buyer.product.detail', $item->product->id) }}" class="hover:text-pink-600">
                                    {{ $item->product->name }}
                                </a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                        </div>
                        <p class="text-sm font-medium text-pink-600">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Shipping & Payment Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Shipping Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Pengiriman</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm font-medium text-gray-500">Nama Penerima</p>
                    <p class="text-base text-gray-900">{{ $order->recipient_name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Alamat Pengiriman</p>
                    <p class="text-base text-gray-900">{{ $order->shipping_address }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">No. Telepon</p>
                    <p class="text-base text-gray-900">{{ $order->recipient_phone }}</p>
                </div>
                @if($order->shipping_method)
                <div>
                    <p class="text-sm font-medium text-gray-500">Metode Pengiriman</p>
                    <p class="text-base text-gray-900">{{ $order->shipping_method }}</p>
                </div>
                @endif
                @if($order->tracking_number)
                <div>
                    <p class="text-sm font-medium text-gray-500">Nomor Resi</p>
                    <p class="text-base text-pink-600">{{ $order->tracking_number }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Informasi Pembayaran</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-sm font-medium text-gray-500">Metode Pembayaran</p>
                    <p class="text-base text-gray-900">{{ $order->payment_method ?? 'Transfer Bank' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Status Pembayaran</p>
                    <p class="text-base font-medium {{ $order->payment_status == 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                        {{ $order->payment_status == 'paid' ? 'Lunas' : 'Belum Dibayar' }}
                    </p>
                </div>
                @if($order->payment_due)
                <div>
                    <p class="text-sm font-medium text-gray-500">Batas Waktu Pembayaran</p>
                    <p class="text-base text-gray-900">{{ $order->payment_due->format('d M Y, H:i') }}</p>
                </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="mt-6 border-t border-gray-200 pt-4">
                <h3 class="text-md font-medium text-gray-900 mb-2">Ringkasan Biaya</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-500">Subtotal Produk</p>
                        <p class="text-sm text-gray-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</p>
                    </div>
                    @if($order->shipping_cost)
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-500">Biaya Pengiriman</p>
                        <p class="text-sm text-gray-900">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</p>
                    </div>
                    @endif
                    @if($order->discount)
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-500">Diskon</p>
                        <p class="text-sm text-green-600">-Rp {{ number_format($order->discount, 0, ',', '.') }}</p>
                    </div>
                    @endif
                    <div class="flex justify-between font-medium">
                        <p class="text-base text-gray-900">Total</p>
                        <p class="text-base text-pink-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Notes -->
    @if($order->notes)
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-2">Catatan</h2>
        <p class="text-gray-700">{{ $order->notes }}</p>
    </div>
    @endif

    <!-- Customer Support -->
    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-gray-900">Ada pertanyaan?</h3>
                <p class="mt-1 text-sm text-gray-500">Jika Anda memiliki pertanyaan tentang pesanan ini, silakan hubungi layanan pelanggan kami.</p>
                <div class="mt-2">
                    <a href="#" class="text-sm font-medium text-pink-600 hover:text-pink-500">Hubungi Kami &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection