<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $casts = [
        'published'  => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
