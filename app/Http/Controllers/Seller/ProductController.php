<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Shop;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();

        // If the user doesn't have a shop yet, redirect to shop creation
        if (!$shop) {
            return redirect()->route('seller.shop.create')
                ->with('warning', 'Please create your shop to manage products.');
        }

        // Get category, search, status filters and perPage
        $category_id = $request->input('category_id');
        $search = $request->input('search');
        $status = $request->input('status');
        $perPage = $request->get('perPage', 10);  // Default to 10 if not specified

        // Build query for products
        $query = Product::where('seller_id', $user->id);
        
        // Apply filters
        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('is_active', $status === 'active');
        }

        // Get products with pagination
        $products = $query->orderBy('created_at', 'desc')->paginate($perPage);

        // Get categories for filtering
        $categories = Category::orderBy('name')->get();

        return view('seller.products.index', compact('products', 'categories', 'shop'));
    }


    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();

        // If the user doesn't have a shop yet, redirect to shop creation
        if (!$shop) {
            return redirect()->route('seller.shop.create')
                ->with('warning', 'Please create your shop to add products.');
        }

        // Get all categories
        $categories = Category::orderBy('name')->get();

        return view('seller.products.create', compact('categories', 'shop'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();

        // Validate product data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0', // Add weight validation
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each image
        ]);

        // Add the seller ID and shop ID to the validated data
        $validated['seller_id'] = $user->id;

        // Set shop_id if shop exists
        if ($shop) {
            $validated['shop_id'] = $shop->id;
        }

        // Set user_id
        $validated['user_id'] = $user->id;

        // Create the product
        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'weight' => $validated['weight'], // Add weight field
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
            'seller_id' => Auth::id(),
        ]);

        // Handle multiple images
        foreach ($request->file('images') as $image) {
            $path = $image->store('products', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_primary' => false, // You can set logic for primary image if needed
            ]);
        }

        return redirect()->route('seller.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();
        // Ensure the product belongs to the user
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('seller.products.show', compact('product', 'shop'));
    }

    public function edit(Product $product)
    {
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();

        // Ensure the product belongs to the user
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get all categories
        $categories = Category::orderBy('name')->get();

        return view('seller.products.edit', compact('product', 'categories', 'shop'));
    }

    public function update(Request $request, Product $product)
    {
        // Ensure the product belongs to the user
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate product data (no image validation here)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0', // Add weight validation
            'is_active' => 'sometimes|boolean',
        ]);

        // Update the product (no need to handle image here)
        $product->update($validated);

        return redirect()->route('seller.products.index')
            ->with('success', 'Product updated successfully!');
    }

    private function generateImageName($user, Request $request)
    {
        // Format the product name to be URL/file friendly
        $productName = strtolower(str_replace(' ', '_', $request->name)); 

        // Add timestamp to make sure the image name is unique
        $timestamp = now()->format('Y_m_d_H_i_s'); 

        // Generate the image name
        return "{$user->name}_{$productName}_{$timestamp}." . $request->file('image')->getClientOriginalExtension();
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        // Ensure the product belongs to the user
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete product image from storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete the product
        $product->delete();

        return redirect()->route('seller.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function showProductImages(Product $product)
    {
        $user = Auth::user();
        $shop = Shop::where('user_id', $user->id)->first();
        // Pastikan produk milik seller yang sedang login
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ambil semua gambar yang terkait dengan produk
        $images = $product->images; // Mengambil semua gambar yang terkait dengan produk

        // Tampilkan view untuk menampilkan gambar-gambar produk
        return view('seller.products.images', compact('product', 'images', 'shop'));
    }

    public function storeUploadedImage(Request $request, Product $product)
    {
        // Validasi gambar yang diunggah
        $request->validate([
            'new_image' => 'required|array', // Pastikan ada beberapa gambar
            'new_image.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi setiap gambar
        ]);

        // Loop untuk menyimpan setiap gambar yang diunggah
        foreach ($request->file('new_image') as $image) {
            // Simpan gambar ke storage
            $path = $image->store('products', 'public');

            // Tambahkan data gambar ke database
            $product->images()->create([
                'image_path' => $path,
            ]);
        }

        return redirect()->route('seller.products.images.list', $product->id)
            ->with('success', 'Images uploaded successfully!');
    }

    public function uploadProductImages(Request $request, Product $product)
    {
        // Ensure the product belongs to the authenticated user
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Validate images input
        $validated = $request->validate([
            'images' => 'required|array', // Ensure multiple images are provided
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each image type and max size
        ]);

        // Loop through each uploaded image
        foreach ($request->file('images') as $image) {
            // Check if the image is valid before proceeding
            if ($image && $image->isValid()) {
                // Use Laravel's default filename for the image
                $path = $image->store('products', 'public');

                // Save the image path to the product_images table
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => 0, // Default to non-primary
                ]);
            } else {
                // Log or return an error if the image is not valid or not uploaded
                return back()->with('error', 'There was an issue uploading one or more images. Please try again.');
            }
        }

        return redirect()->route('seller.products.images.list', $product->id)
            ->with('success', 'Images uploaded successfully!');
    }


    public function updatePrimaryImage(Product $product, ProductImage $image)
    {
        // Ensure the product belongs to the authenticated user
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Set all images as non-primary
        ProductImage::where('product_id', $product->id)->update(['is_primary' => 0]);

        // Set the selected image as primary
        $image->update(['is_primary' => 1]);

        return redirect()->route('seller.products.images.list', $product->id)
            ->with('success', 'Primary image updated successfully!');
    }

    public function destroyImage(Product $product, ProductImage $image)
    {
        // Ensure the product belongs to the authenticated user
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image file from storage
        Storage::disk('public')->delete($image->image_path);

        // Delete the image record from database
        $image->delete();

        return redirect()->route('seller.products.images.list', $product->id)
            ->with('success', 'Image deleted successfully!');
    }
    
}