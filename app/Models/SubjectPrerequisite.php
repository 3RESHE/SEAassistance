<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectPrerequisite extends Model
{
    protected $fillable = [
        'subject_id',
        'prerequisite_subject_id',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function prerequisiteSubject()
    {
        return $this->belongsTo(Subject::class, 'prerequisite_subject_id');
    }
}
