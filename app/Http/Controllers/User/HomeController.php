<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display homepage with products and categories
     */
    public function index()
    {
        $featuredProducts = Product::with('images')
                                ->where('is_active', 1)
                                ->orderBy('created_at', 'DESC')
                                ->take(8)
                                ->get();
                                
        $categories = Category::all();
        $products = Product::with('images')
                        ->where('is_active', 1)
                        ->get();
        
        return view('user.home', [
            'title' => 'Toko Kue - Beranda',
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function landing()
    {
        $featuredProducts = Product::with('images')
                                ->where('is_active', 1)
                                ->orderBy('created_at', 'DESC')
                                ->take(8)
                                ->get();
                                
        $categories = Category::all();
        $products = Product::with('images')
                        ->where('is_active', 1)
                        ->get();
        
        return view('welcome', [
            'title' => 'Toko Kue - Beranda',
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'products' => $products
        ]);
    }
    
    /**
     * Display all products
     */
    public function products()
    {
        $products = Product::where('is_active', 1)->get();
        $categories = Category::all();
        
        return view('user.products.home', [
            'title' => 'Semua Produk Kue',
            'products' => $products,
            'categories' => $categories
        ]);
    }
    
    /**
     * Filter products by category
     */
    public function category($categoryId)
    {
        $category = Category::find($categoryId);
        
        if (!$category) {
            return redirect()->route('user.products')->with('error', 'Kategori tidak ditemukan');
        }
        
        $products = Product::where('category_id', $categoryId)
                         ->where('is_active', 1)
                         ->get();
                         
        $categories = Category::all();
        
        return view('user.products.category', [
            'title' => 'Produk Kategori: ' . $category->name,
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $category
        ]);
    }
    
    /**
     * Show single product detail
     */
    public function product($id)
    {
        $product = Product::find($id);
        
        if (!$product || $product->is_active == 0) {
            return redirect()->route('user.products')->with('error', 'Produk tidak ditemukan');
        }
        
        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
                                ->where('id', '!=', $id)
                                ->where('is_active', 1)
                                ->take(4)
                                ->get();
        
        $category = Category::find($product->category_id);
        
        return view('user.products.show', [
            'title' => $product->name,
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'category' => $category
        ]);
    }
    
    /**
     * Search for products
     */
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        
        if (empty($keyword)) {
            return redirect()->route('user.products');
        }
        
        $products = Product::where('name', 'like', '%' . $keyword . '%')
                         ->orWhere('description', 'like', '%' . $keyword . '%')
                         ->where('is_active', 1)
                         ->get();
        
        $categories = Category::all();
        
        return view('user.home.search_results', [
            'title' => 'Hasil Pencarian: ' . $keyword,
            'products' => $products,
            'categories' => $categories,
            'keyword' => $keyword
        ]);
    }
    
    /**
     * Display about us page
     */
    public function about()
    {
        return view('user.home.about', [
            'title' => 'Tentang Kami'
        ]);
    }
    
    /**
     * Display contact page
     */
    public function contact()
    {
        return view('user.home.contact', [
            'title' => 'Hubungi Kami'
        ]);
    }
    
    /**
     * Send contact message
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3|max:100',
            'email' => 'required|email',
            'subject' => 'required|min:5|max:100',
            'message' => 'required|min:10'
        ]);
        
        // Di sini bisa ditambahkan logika untuk menyimpan pesan ke database
        // atau mengirim email ke admin
        
        return redirect()->route('user.contact')->with('success', 'Pesan Anda telah terkirim. Kami akan menghubungi Anda segera.');
    }
    
    /**
     * Display FAQ page
     */
    public function faq()
    {
        return view('user.home.faq', [
            'title' => 'FAQ - Pertanyaan yang Sering Diajukan'
        ]);
    }
}