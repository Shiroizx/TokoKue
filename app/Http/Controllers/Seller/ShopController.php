<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Display the shop creation form or redirect to dashboard if shop exists.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Directly query the shop
        $shop = Shop::where('user_id', $user->id)->first();
        
        // Check if user already has a shop - redirect to dashboard instead of edit page
        if ($shop) {
            return redirect()->route('seller.shop.edit')
                ->with('info', 'You already have a shop. Redirected to dashboard.');
        }

        return view('seller.shop.create');
    }

    /**
     * Store a newly created shop.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if user already has a shop before creating a new one
        $existingShop = Shop::where('user_id', $user->id)->first();
        
        if ($existingShop) {
            return redirect()->route('seller.dashboard')
                ->with('info', 'You already have a shop. Redirected to dashboard.');
        }
        
        // Validate shop data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('shops/logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('shops/banners', 'public');
            $validated['banner'] = $bannerPath;
        }

        // Create the shop with the user ID
        $validated['user_id'] = $user->id;
        $validated['slug'] = Str::slug($validated['name']) . '-' . uniqid();
        
        $shop = Shop::create($validated);

        return redirect()->route('seller.dashboard')
            ->with('success', 'Shop created successfully!');
    }

    /**
     * Display the shop edit form.
     */
    public function edit()
    {
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();

        // Jika pengguna belum memiliki toko, redirect ke halaman pembuatan toko
        if (!$shop) {
            return redirect()->route('seller.shop.create')
                ->with('warning', 'Silakan buat toko Anda terlebih dahulu.');
        }

        // Inisialisasi variabel dengan nilai default untuk mencegah "undefined variable"
        $productsCount = 0;
        $ordersCount = 0;
        
        // Gunakan try-catch untuk menangani error yang mungkin terjadi
        try {
            // Menghitung produk milik penjual ini
            $productsCount = Product::where('seller_id', $user->id)->count();
        } catch (\Exception $e) {
            // Tetap gunakan nilai default jika terjadi error
        }
        
        try {
            // Menghitung pesanan milik penjual ini
            $ordersCount = Order::where('seller_id', $user->id)->count();
        } catch (\Exception $e) {
            // Tetap gunakan nilai default jika terjadi error
        }

        return view('seller.shop.edit', compact('shop', 'productsCount', 'ordersCount'));
    }

    /**
     * Update the shop.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();

        // If the user doesn't have a shop yet, redirect to shop creation
        if (!$shop) {
            return redirect()->route('seller.shop.create')
                ->with('warning', 'Please create your shop first.');
        }

        // Validate shop data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($shop->logo) {
                Storage::disk('public')->delete($shop->logo);
            }
            
            $logoPath = $request->file('logo')->store('shops/logos', 'public');
            $validated['logo'] = $logoPath;
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            // Delete old banner if exists
            if ($shop->banner) {
                Storage::disk('public')->delete($shop->banner);
            }
            
            $bannerPath = $request->file('banner')->store('shops/banners', 'public');
            $validated['banner'] = $bannerPath;
        }

        // Update the shop
        $shop->update($validated);

        // Redirect to dashboard instead of back to edit page
        return redirect()->route('seller.dashboard')
            ->with('success', 'Shop updated successfully!');
    }
}