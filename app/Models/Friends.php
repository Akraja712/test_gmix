<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Friends extends Model
{
    use Notifiable;

    protected $table = 'friends';

    protected $fillable = [
        'friend_user_id', 'user_id', 'datetime','status',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    
    public function friendUser()
    {
        return $this->belongsTo(Users::class, 'friend_user_id');
    }
}

