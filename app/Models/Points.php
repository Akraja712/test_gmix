<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Points extends Model
{
    protected $fillable = [
        'points',
        'offer_percentage',
        'price',
        'datetime'
    ];
}
