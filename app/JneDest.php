<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JneDest extends Model
{
    protected $fillable = [
        'country',
        'province',
        'city',
        'district',
        'sub_district',
        'zip',
        'tarif_code',
    ];
}
