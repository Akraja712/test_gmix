<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Staffs extends Authenticatable
{
    use Notifiable;

    protected $table = 'staffs';

    protected $fillable = [
        'name', 'mobile', 'password',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    public function getFullname()
    {
        return $this->name . ' ' . $this->last_name;
    }
    public function getAvatar()
    {
        return 'https://www.gravatar.com/avatar/' . md5($this->email);
    }
}

