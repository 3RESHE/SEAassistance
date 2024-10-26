<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TorRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'subjects', 'status'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
