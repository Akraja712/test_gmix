<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationTrans extends Model
{
    use HasFactory;

    protected $table = 'verification_trans';

    protected $fillable = [
        'user_id',
        'txn_id',
        'order_id',
        'amount',
        'status',
        'txn_date',
        'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
