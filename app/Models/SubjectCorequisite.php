<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectCorequisite extends Model
{
    use HasFactory;

    protected $fillable = ['subject_id', 'corequisite_subject_id'];

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function corequisiteSubject()
    {
        return $this->belongsTo(Subject::class, 'corequisite_subject_id');
    }
}
