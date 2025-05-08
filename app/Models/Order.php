<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status', // 'pending_payment', 'processing', 'shipped', 'completed', 'cancelled'
        'subtotal',
        'shipping_cost',
        'total',
        
        // Recipient Information
        'recipient_name',
        'recipient_phone',
        
        // Shipping Address Details
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'district',
        'village',
        'postal_code',
        'detailed_address',
        'residence_type', // 'house', 'apartment', 'kos', 'rent'
        
        // Shipping Details
        'shipping_courier',
        'shipping_service',
        'total_weight',
        
        // Payment Details
        'payment_method',
        'payment_status', // 'unpaid', 'paid', 'refunded'
        'tracking_number',

        // Updated At
        'shipped_at',
        'delivered_at',
        'canceled_at',
    ];

    // Optional: Define enum casts if you want to enforce specific values
    protected $casts = [
        'status' => 'string',
        'residence_type' => 'string',
        'payment_status' => 'string',
        'total_weight' => 'float',
        'subtotal' => 'float',
        'shipping_cost' => 'float',
        'total' => 'float',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Optional: Accessor for formatted total
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', '.');
    }

    // Optional: Scope for different order statuses
    public function scopePendingPayment($query)
    {
        return $query->where('status', 'pending_payment');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    
    public function getSubtotalAttribute($value)
    {
        // Jika nilai sudah ada di database, gunakan nilai tersebut
        if ($value > 0) {
            return $value;
        }
        
        // Jika tidak, hitung dari order items
        return $this->orderItems->sum(function($item) {
            return $item->price * $item->quantity;
        });
    }
}