<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward_products extends Model
{
    protected $fillable = [
        'points',
        'name',
        'description',
        'image',
    ];
}
