<!-- Desktop Checkout Steps -->
<div class="container mx-auto px-4">
    <div class="w-full flex items-center justify-center">
        <div class="w-full max-w-2xl">
            <div class="flex items-center">
                <!-- Keranjang Step -->
                <div class="flex-1 flex items-center">
                    <div class="w-10 h-10 {{ $current_step == 'cart' ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center z-10">
                        1
                    </div>
                    <div class="flex-1 h-1 {{ $current_step == 'cart' ? 'bg-pink-500' : 'bg-gray-200' }} mx-2"></div>
                    <span class="text-sm {{ $current_step == 'cart' ? 'text-pink-500' : 'text-gray-500' }}">Keranjang</span>
                </div>

                <!-- Pengiriman Step -->
                <div class="flex-1 flex items-center">
                    <div class="w-10 h-10 {{ $current_step == 'shipping' ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center z-10">
                        2
                    </div>
                    <div class="flex-1 h-1 {{ $current_step == 'shipping' ? 'bg-pink-500' : 'bg-gray-200' }} mx-2"></div>
                    <span class="text-sm {{ $current_step == 'shipping' ? 'text-pink-500' : 'text-gray-500' }}">Pengiriman</span>
                </div>

                <!-- Pembayaran Step -->
                <div class="flex-1 flex items-center">
                    <div class="w-10 h-10 {{ $current_step == 'payment' ? 'bg-pink-500 text-white' : 'bg-gray-200 text-gray-500' }} rounded-full flex items-center justify-center z-10">
                        3
                    </div>
                    <span class="text-sm ml-2 {{ $current_step == 'payment' ? 'text-pink-500' : 'text-gray-500' }}">Pembayaran</span>
                </div>
            </div>
        </div>
    </div>
</div>