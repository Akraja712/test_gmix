<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'mobile',
        'alternate_mobile',
        'door_no',
        'street_name',
        'city',
        'pincode',
        'landmark',
        'state',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    
    public function orders()
    {
        return $this->belongsTo(Users::class, 'order_id');
    }
}
