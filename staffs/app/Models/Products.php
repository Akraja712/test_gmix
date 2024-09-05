<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'measurement',
        'price',
        'delivery_charges',
        'image',
    ];

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
