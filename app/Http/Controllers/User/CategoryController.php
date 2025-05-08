<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display products by category.
     */
    public function products($id)
    {
        $category = Category::findOrFail($id);
        
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)  // Changed from 'status' to 'is_active'
            ->with('images')
            ->paginate(12);
            
        return view('user.category.products', [
            'category' => $category,
            'products' => $products,
            'title' => $category->name . ' - Djawa Cake'
        ]);
    }
    
    /**
     * Display all categories.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();
            
        return view('user.category.index', [
            'categories' => $categories,
            'title' => 'Categories - Djawa Cake'
        ]);
    }
}