<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'curriculum_id', 'year_term', 'descriptive_title', 'subject_code', 'units','required_year_level', 'year'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function prerequisites()
    {
        return $this->belongsToMany(Subject::class, 'subject_prerequisites', 'subject_id', 'prerequisite_subject_id');
    }

    public function corequisites()
    {
        return $this->belongsToMany(Subject::class, 'subject_corequisites', 'subject_id', 'corequisite_subject_id');
    }

    public function curriculums()
    {
        return $this->belongsToMany(Curriculum::class, 'curriculum_subjects', 'subject_id', 'curriculum_id');
    }
    public function curriculumSubjects()
    {
        return $this->hasMany(CurriculumSubject::class);
    }

    public function advisings()
{
    return $this->belongsToMany(Advising::class, 'advising_details', 'subject_id', 'advising_id')
        ->withPivot('status', 'units')
        ->withTimestamps();
}

}
