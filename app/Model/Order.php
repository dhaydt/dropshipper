<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $casts = [
        'order_amount' => 'float',
        'discount_amount' => 'float',
        'customer_id' => 'integer',
        'shipping_address' => 'integer',
        'shipping_cost' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class)->orderBy('seller_id', 'ASC');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function sellerName()
    {
        return $this->hasOne(OrderDetail::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function shipping()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }
    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address');
    }
}
