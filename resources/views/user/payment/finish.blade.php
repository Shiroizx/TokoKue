@extends('user.layouts.app')

@section('title', 'Pembayaran Berhasil')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-lg mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-8 text-center">
            <div class="mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 mx-auto text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-4">Pembayaran Berhasil</h2>
            
            @if($order)
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Nomor Pesanan</span>
                        <span class="font-medium text-gray-800">{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Total Pembayaran</span>
                        <span class="font-bold text-pink-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status</span>
                        <span class="font-medium text-green-600">Lunas</span>
                    </div>
                </div>
            @else
                <p class="text-gray-600 mb-6">Tidak ada pesanan yang ditemukan.</p>
            @endif

            <div class="space-y-4">
                <a href="{{ route('buyer.order.index') }}" class="w-full px-6 py-3 bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-md hover:from-pink-600 hover:to-pink-700 transition-all duration-200 block">
                    Lihat Pesanan
                </a>
                <a href="{{ route('buyer.home') }}" class="w-full px-6 py-3 border border-pink-500 text-pink-600 rounded-md hover:bg-pink-50 transition-all duration-200 block">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection