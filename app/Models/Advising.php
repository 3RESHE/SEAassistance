<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advising extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'advisor_id', 'unit_limit', 'advising_status','load_status']; // Include advisor_id

    public function details()
    {
        return $this->hasMany(AdvisingDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Relationship to the student
    }

    // Relationship to the advisor
    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id'); // Relationship to the advisor
    }

    // Update the subjects relationship
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'advising_details', 'advising_id', 'subject_id')
            ->withPivot('status', 'units')
            ->withTimestamps();
    }

    

}
