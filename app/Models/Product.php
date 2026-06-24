<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'ali_link',
        'size',
        'color',
        'gender',
        'quantity',
        'custom_note',
        'screenshot',
        'status',
        'quote_expires_at',
        'rejection_reason',
        'rejected_at',

        // admin fields
        'title',
        'image',
        'price_usd',
        'shipping_usd',
        'final_price_dzd',
        'service_fee_dzd',
        'rate_used',
    ];

    protected $casts = [
        'quote_expires_at' => 'datetime',
        'rejected_at' => 'datetime',
        'price_usd' => 'decimal:2',
        'shipping_usd' => 'decimal:2',
        'final_price_dzd' => 'decimal:2',
        'service_fee_dzd' => 'decimal:2',
        'rate_used' => 'decimal:2',
    ];

    /*
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    */

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
