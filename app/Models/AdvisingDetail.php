<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisingDetail extends Model
{
    use HasFactory;

    protected $fillable = ['advising_id', 'subject_id', 'status', 'units'];

    public function advising()
    {
        return $this->belongsTo(Advising::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
