<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'Name',
        'Email',
        'Password',
    ];


    public function StudentProgress(){
        return $this->hasOne(StudentProgress::class, 'StudentProgressId');
    }

    public function Notes(){
        return $this->hasOne(Notes::class, 'NotesId');
    }

    public function Test(){
        return $this->hasMany(Test::class, 'TestId');
    }

    public function Attendance(){
        return $this->hasMany(Attendance::class, 'AttendanceId');
    }

    public function Announcement(){
        return $this->hasMany(Announcement::class, 'AnnouncementId');
    }

    public function UserTask(){
        return $this->hasMany(UserTask::class, 'UserTaskId');
    }
    public function Enrollment(){
        return $this->hasMany(Enrollment::class, 'EnrollmentId');
    }

    public function Complaint(){
        return $this->hasMany(Complaint::class, 'ComplaintId');
    }

    public function PlacementTest(){
        return $this->hasMany(PlacementTest::class, 'PlacementTestId');
    }

    public function Course(){
        return $this->hasMany(Course::class, 'CourseId');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
