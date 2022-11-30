<?php

namespace App;

use App\Model\Product;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';

    public function product()
    {
        return $this->hasMany(Product::class, 'country', 'country');
    }
}