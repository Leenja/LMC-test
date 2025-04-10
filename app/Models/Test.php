<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [];

    public function User(){
        return $this->belongsTo(User::class, 'UserId');
    }

    public function Course(){
        return $this->hasOne(Course::class, 'CourseId');
    }
}
