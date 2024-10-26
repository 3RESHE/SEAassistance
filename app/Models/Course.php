<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['department_id', 'course_name', 'course_acronym'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function curriculums()
    {
        return $this->hasMany(Curriculum::class);
    }

    public function users()
{
    return $this->hasMany(User::class);
}



}
