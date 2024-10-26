<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'is_bot',
        'is_admin',
    ];

    // Define the relationship with User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
