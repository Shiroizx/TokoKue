<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Order; // Add this for checking orders
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        // Get category, search, status filters, and perPage
        $category_id = $request->input('category_id');
        $search = $request->input('search');
        $status = $request->input('status');
        $perPage = $request->get('perPage', 10);  // Default to 10 if not specified

        // Build query for products
        $query = Product::with(['category', 'seller']);
        
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

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        // Get all categories and sellers for form
        $categories = Category::all();
        $sellers = User::where('role', 'seller')->get(); // Assuming sellers are a separate user role

        return view('admin.products.create', compact('categories', 'sellers'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        // Validate product data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0', // Added weight validation
            'seller_id' => 'required|exists:users,id', // Admin chooses the seller
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate each image type and max size
        ]);

        // Create the product in the database
        $product = Product::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'weight' => $validated['weight'], // Add weight field
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,  // Default to true if not provided
            'seller_id' => $validated['seller_id'], // Seller picked by the admin
        ]);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                // Store the image
                $path = $image->store('products', 'public');

                // Create a new record in the product_images table
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $index === 0, // The first image will be marked as primary
                ]);
            }
        }

        // Redirect back to the product index with success message
        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Show product details
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        // Get all categories and sellers for form edit
        $categories = Category::all();
        $sellers = User::where('role', 'seller')->get();

        return view('admin.products.edit', compact('product', 'categories', 'sellers'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Validate product data (no image validation here)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0', // Added weight validation
            'is_active' => 'sometimes|boolean',
            'seller_id' => 'required|exists:users,id', // Admin chooses seller
        ]);

        // If seller has changed, update orders associated with this product
        if ($product->seller_id != $validated['seller_id']) {
            // This would be implemented in a real application to transfer orders
            // to the new seller if confirmed by the admin
            // For example:
            // Order::where('product_id', $product->id)->update(['seller_id' => $validated['seller_id']]);
        }

        // Update the product
        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete product image from storage if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete the product
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Show all images for a specific product.
     */
    public function showProductImages(Product $product)
    {
        // Fetch all images related to the product
        $images = $product->images; // All product images

        // Show images in the view
        return view('admin.products.images', compact('product', 'images'));
    }

    /**
     * Store a new image for a product.
     */
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

        return redirect()->route('admin.products.images.list', $product->id)
            ->with('success', 'Images uploaded successfully!');
    }


    /**
     * Upload multiple images for a product.
     */
    public function uploadProductImages(Request $request, Product $product)
    {
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

        return redirect()->route('admin.products.images.list', $product->id)
            ->with('success', 'Images uploaded successfully!');
    }

    /**
     * Set the primary image for a product.
     */
    public function updatePrimaryImage(Product $product, ProductImage $image)
    {
        // Set all images as non-primary
        ProductImage::where('product_id', $product->id)->update(['is_primary' => 0]);

        // Set the selected image as primary
        $image->update(['is_primary' => 1]);

        return redirect()->route('admin.products.images.list', $product->id)
            ->with('success', 'Primary image updated successfully!');
    }

    /**
     * Delete a product image.
     */
    public function destroyImage(Product $product, ProductImage $image)
    {
        // Delete image file from storage
        Storage::disk('public')->delete($image->image_path);

        // Delete the image record from the database
        $image->delete();

        return redirect()->route('admin.products.images.list', $product->id)
            ->with('success', 'Image deleted successfully!');
    }
}