<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'user_id',
        'notify_user_id',
        'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function notifyUser()
    {
        return $this->belongsTo(Users::class, 'notify_user_id');
    }
}
