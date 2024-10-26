<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name',
        'department_logo',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function user_department()
{
    return $this->belongsToMany(User::class, 'department_user');
}

    public function user()
{
    return $this->belongsToMany(User::class);
}


}
