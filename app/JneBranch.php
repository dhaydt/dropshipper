<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JneBranch extends Model
{
    protected $fillable = [
        'branch_code', 'branch_name'
    ];
}
