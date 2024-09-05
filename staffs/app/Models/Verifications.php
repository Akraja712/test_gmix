<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'selfie_image',
        'front_image',
        'back_image',
        'payment_image',
        'status',
        'plan_id',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plans::class);
    }
}
