<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'school_id', 'email', 'name', 'last_name', 'middle_name', 
        'role', 'department_id', 'course_id', 
        'curriculum_id', 'year', 'semester', 'password', 'name_suffix', 
        'academic_year', 'user_status', 'student_type'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_user', 'user_id', 'department_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

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

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'grades')->withPivot('grade');
    }

    public function advisor()
    {
        return $this->belongsTo(Advising::class, 'advisor_id');
    }


    // In App\Models\User.php

public function torRequests()
{
    return $this->hasMany(TorRequest::class);
}



    public function isAdmin() {
        return $this->role === 'admin';
    }
    
    public function isStudent() {
        return $this->role === 'student';
    }

    public function sentMessages() {
        return $this->hasMany(Message::class, 'sender_id');
    }
    
    public function receivedMessages() {
        return $this->hasMany(Message::class, 'receiver_id');
    }
    
















}
