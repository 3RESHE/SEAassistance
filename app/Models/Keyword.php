<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $fillable = ['keyword'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
