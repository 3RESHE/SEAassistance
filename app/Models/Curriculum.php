<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'curriculum_name'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function curriculumSubjects()
    {
        return $this->hasMany(CurriculumSubject::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'curriculum_subjects', 'curriculum_id', 'subject_id')
                    ->withPivot('year', 'year_term'); // Access year and term from pivot
    }

    public function users()
{
    return $this->hasMany(User::class);
}


}


