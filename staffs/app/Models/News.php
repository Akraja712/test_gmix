<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'telegram', 'instagram','privacy_policy','terms_conditions','upi_id',
    ];
}   
