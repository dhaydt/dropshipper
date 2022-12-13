<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RequestProduct extends Model
{
    protected $fillable = [
        'name',
            'qty',
            'phone',
            'link',
            'description',
            'status',
            'image',
    ];
}
