<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Chat_points extends Model
{
    use Notifiable;

    protected $table = 'chat_points';

    protected $fillable = [
        'chat_user_id', 'user_id', 'datetime','points',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    public function chatUser()
    {
        return $this->belongsTo(Users::class, 'chat_user_id');
    }
}

