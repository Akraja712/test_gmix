<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OTP extends Model
{
    use Notifiable;

    protected $table = 'otp';

    protected $fillable = [
        'otp', 'mobile', 'datetime',
    ];

}

