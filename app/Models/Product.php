<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'is_active',
        'seller_id',
        'weight',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Di model Product.php
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'seller_id', 'id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasMany(ProductImage::class, 'product_id')
                    ->where('is_primary', 1)
                    ->first();
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists')
                    ->withTimestamps();
    }

    public function wishlistCount()
    {
        return $this->wishlists()->count();
    }

}