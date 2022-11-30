<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{

    protected $casts = [
        'product_id'  => 'integer',
        'customer_id' => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
