<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    protected $fillable = [
        'name',
        'mobile',
        'alternate_mobile',
        'door_no',
        'street_name',
        'city',
        'pincode',
        'state',
        'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}
