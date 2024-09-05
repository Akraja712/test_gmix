<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Feedback extends Model
{
    use Notifiable;

    protected $table = 'feedback';

    protected $fillable = [
        'feedback', 'user_id', 
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
}

