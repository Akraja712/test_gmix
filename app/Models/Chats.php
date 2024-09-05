<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Chats extends Model
{
    use Notifiable;

    protected $table = 'chats';

    protected $fillable = [
        'latest_message', 'user_id', 'datetime','unread',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}

