<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StaffRepository
{

    //Secretary----------------------------------------------

    //Enrollment
    public function updateUserRole($studentId, $role)
    {
        $user = User::findOrFail($studentId);
        $user->role = $role;
        $user->save();
        return $user;
    }

    public function createEnrollment($data)
    {
        return Enrollment::create([
            'StudentId' => $data['StudentId'],
            'CourseId' => $data['CourseId'],
            'isPrivate' => $data['isPrivate'],
        ]);
    }

    //Add course
    public function createCourse($data)
    {
        return Course::create([
            'TeacherId' => $data['TeacherId'],
            'LanguageId' => $data['LanguageId'],
            'Description' => $data['Description'],
            'Level' => $data['Level'],
            'Status' => 'Unactive',
        ]);
    }

    public function createSchedule($courseId, $data)
    {
        return DB::table('courseschedules')->insert([
            'CourseId' => $courseId,
            'RoomId' => $data['RoomId'],
            'Start_Enroll' => $data['Start_Enroll'],
            'End_Enroll' => $data['End_Enroll'],
            'Start_Date' => $data['Start_Date'],
            'End_Date' => $data['End_Date'],
            'Start_Time' => $data['Start_Time'],
            'End_Time' => $data['End_Time'],
            'CourseDays' => json_encode($data['CourseDays']),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function calculateCourseEndDate($startDate, $daysOfWeek, $numberOfLessons)
    {
        $date = Carbon::parse($startDate);
        $count = 0;

        while ($count < $numberOfLessons) {
            if (in_array($date->format('D'), $daysOfWeek)) {
                $count++;
            }
            $date->addDay();
        }

        return $date->subDay(); // go back to last valid day
    }

    //Edit course
    public function updateCourseSchedule($courseId, $data)
    {
        return DB::table('courseschedules')
            ->where('CourseId', $courseId)
            ->update([
                'Start_Enroll' => $data['Start_Enroll'],
                'End_Enroll' => $data['End_Enroll'],
                'Start_Date' => $data['Start_Date'],
                'End_Date' => $data['End_Date'],
                'Start_Time' => $data['Start_Time'],
                'End_Time' => $data['End_Time'],
                'CourseDays' => json_encode($data['CourseDays']),
                'updated_at' => now(),
            ]);
    }

    //Conflict
    public function checkCourseScheduleConflict($roomId, $startDate, $endDate, $courseDays)
    {
        // First, make sure the course doesn't conflict with any other course in the same room during the same timeslot
        return DB::table('courseschedules')
            ->where('RoomId', $roomId)
            ->where(function ($query) use ($startDate, $endDate, $courseDays) {
                // Check if the new course start and end dates overlap with an existing course's start and end dates
                $query->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('Start_Date', [$startDate, $endDate])
                    ->orWhereBetween('End_Date', [$startDate, $endDate])
                    ->orWhere(function($q2) use ($startDate, $endDate) {
                        $q2->where('Start_Date', '<=', $endDate)
                            ->where('End_Date', '>=', $startDate);
                    });
                });

                // Check if any of the course days overlap
                $query->whereRaw("JSON_CONTAINS(CourseDays, '\"$courseDays\"')");
            })
            ->exists();
    }

}
