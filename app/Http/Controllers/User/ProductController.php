<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // Tambahkan import untuk model Product

class ProductController extends Controller
{
    public function show(Product $product)
    {
        return view('user.products.show', compact('product'));
    }
}