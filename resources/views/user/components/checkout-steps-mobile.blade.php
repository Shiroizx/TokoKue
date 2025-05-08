<!-- Mobile Checkout Steps -->
<div class="container mx-auto px-4">
    <div class="w-full flex items-center justify-between">
        <!-- Keranjang Step -->
        <div class="flex flex-col items-center">
            <div class="w-8 h-8 {{ $current_step == 'cart' ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center">
                1
            </div>
            <span class="text-xs mt-1 {{ $current_step == 'cart' ? 'text-pink-500' : 'text-gray-500' }}">Keranjang</span>
        </div>

        <!-- Pengiriman Step -->
        <div class="flex flex-col items-center">
            <div class="w-8 h-8 {{ $current_step == 'shipping' ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center">
                2
            </div>
            <span class="text-xs mt-1 {{ $current_step == 'shipping' ? 'text-pink-500' : 'text-gray-500' }}">Pengiriman</span>
        </div>

        <!-- Pembayaran Step -->
        <div class="flex flex-col items-center">
            <div class="w-8 h-8 {{ $current_step == 'payment' ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center">
                3
            </div>
            <span class="text-xs mt-1 {{ $current_step == 'payment' ? 'text-pink-500' : 'text-gray-500' }}">Pembayaran</span>
        </div>
    </div>
</div>