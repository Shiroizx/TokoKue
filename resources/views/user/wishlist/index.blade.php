@extends('user.layouts.app')

@section('title', 'My Wishlist - Toko Kue Kelompok 2')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">My Wishlist</h1>
            <p class="text-gray-600 mt-2">Your saved favorite cakes</p>
        </div>
        
        @if(count($wishlists) > 0)
            <div>
                <form action="{{ route('buyer.wishlist.clear') }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to clear your wishlist?')"
                            class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors">
                        <i class="fas fa-trash-alt mr-2"></i> Clear Wishlist
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Wishlist Items -->
    @if(count($wishlists) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($wishlists as $wishlist)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow" 
                     data-id="{{ $wishlist->product->id }}">
                    <div class="relative">
                        <!-- Product Image -->
                        @if($wishlist->product->images->count() > 0)
                            <img src="{{ asset('storage/' . $wishlist->product->images->first()->image_path) }}" 
                                 alt="{{ $wishlist->product->name }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <img src="{{ asset('images/placeholder-cake.jpg') }}" 
                                 alt="{{ $wishlist->product->name }}"
                                 class="w-full h-48 object-cover">
                        @endif

                        <!-- Remove from Wishlist Button -->
                        <button onclick="removeFromWishlist({{ $wishlist->product->id }})"
                                class="absolute top-3 right-3 w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md hover:bg-red-100 transition-colors">
                            <i class="fas fa-times text-red-500"></i>
                        </button>

                        <!-- Wishlist Date -->
                        <div class="absolute bottom-3 left-3 bg-white bg-opacity-75 px-3 py-1 rounded-full">
                            <span class="text-xs text-gray-600">
                                Added {{ $wishlist->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            {{ $wishlist->product->name }}
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-4">
                            {{ Str::limit($wishlist->product->description, 100) }}
                        </p>

                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <span class="text-2xl font-bold text-blue-600">
                                    Rp {{ number_format($wishlist->product->price, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Product Actions -->
                        <div class="pt-4 border-t border-gray-200">
                            <a href="{{ route('buyer.products.show', $wishlist->product->id) }}" 
                               class="text-blue-500 hover:text-blue-600 text-sm">
                                <i class="fas fa-eye mr-1"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination if needed -->
        @if($wishlists->hasPages())
            <div class="mt-8">
                {{ $wishlists->links() }}
            </div>
        @endif
    @else
        <!-- Empty Wishlist State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="mb-6">
                <i class="fas fa-heart text-6xl text-gray-300"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Your wishlist is empty</h2>
            <p class="text-gray-600 mb-8">Start adding your favorite cakes to your wishlist!</p>
            <a href="{{ route('buyer.products') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg inline-block transition-colors">
                <i class="fas fa-shopping-bag mr-2"></i> Browse Products
            </a>
        </div>
    @endif
</div>

<!-- JavaScript for Wishlist Toggle -->
<script>
function removeFromWishlist(productId) {
    console.log('Removing product ID:', productId);
    
    fetch('{{ route("buyer.wishlist.remove") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            // Remove the card from the DOM
            const wishlistCard = document.querySelector(`[data-id="${productId}"]`);
            if(wishlistCard) {
                wishlistCard.remove();
            }
            
            // Check if wishlist is empty
            const gridContainer = document.querySelector('.grid');
            if(gridContainer && gridContainer.children.length === 0) {
                window.location.reload();
            }
            
            // Show notification
            showNotification(data.message, 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Failed to remove from wishlist', 'error');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white transform transition-transform duration-300`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('translate-y-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
@endsection