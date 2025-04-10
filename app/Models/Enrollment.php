<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        "StudentId",
        "CourseId",
        "isPrivate",
    ];

    public function Course(){
        return $this->belongsTo(Course::class, 'CourseId');
    }

    public function User(){
        return $this->belongsTo(User::class, 'UserId');
    }
    public function Certificate(){
        return $this->hasMany(Certificate::class, 'CertificateId');
    }
}
