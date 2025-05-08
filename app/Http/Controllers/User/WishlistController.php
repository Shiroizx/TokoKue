<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the user's wishlist.
     */
    public function index()
    {
        // Gunakan paginate() jika ingin pagination
        $wishlists = Auth::user()->wishlists()
            ->with('product.images')
            ->orderBy('created_at', 'desc')
            ->paginate(12); // Menggunakan paginate() dengan 12 item per halaman
        
        return view('user.wishlist.index', compact('wishlists'));
    }

    /**
     * Add a product to the wishlist.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        // Check if already in wishlist
        if ($user->hasInWishlist($productId)) {
            return response()->json([
                'status' => 'info',
                'message' => 'Product is already in your wishlist'
            ]);
        }

        // Add to wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product added to wishlist successfully'
        ]);
    }

    /**
     * Remove a product from the wishlist.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product removed from wishlist'
        ]);
    }

    /**
     * Toggle product in wishlist.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $user = Auth::user();
        $productId = $request->product_id;

        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Produk dihapus dari wishlist';
            $action = 'removed';
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            $message = 'Produk ditambahkan ke wishlist';
            $action = 'added';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'action' => $action
        ]);
    }

    /**
     * Clear all products from wishlist.
     */
    public function clear()
    {
        Auth::user()->wishlists()->delete();

        return redirect()->route('buyer.wishlist.index')
            ->with('success', 'All products removed from wishlist');
    }
}