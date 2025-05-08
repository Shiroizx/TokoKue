<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'banner',
        'phone_number',
        'email',
        'address',
        'city',
        'province',
        'postal_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shop) {
            if (empty($shop->slug)) {
                $shop->slug = Str::slug($shop->name) . '-' . uniqid();
            }
        });

        static::updating(function ($shop) {
            if ($shop->isDirty('name') && empty($shop->slug)) {
                $shop->slug = Str::slug($shop->name) . '-' . uniqid();
            }
        });
    }

    /**
     * Get the user that owns the shop.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the products for the shop.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id', 'id');
    }

    /**
     * Get the orders for the shop.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id', 'id');
    }
}