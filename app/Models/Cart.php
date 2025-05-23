<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    /**
     * Atribut yang bisa diisi secara massal
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price'
    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}