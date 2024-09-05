<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    protected $fillable = [
        'plan_name',
        'validity',
        'price',
        'save_amount'
    ];
}
