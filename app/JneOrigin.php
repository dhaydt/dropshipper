<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JneOrigin extends Model
{
    protected $fillable = [
        'origin_code',
        'origin_name',
    ];
}
