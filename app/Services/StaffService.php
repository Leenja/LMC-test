<?php

namespace App\Services;

use App\Repositories\StaffRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Lesson;

class StaffService
{
    private $staffRepository;

    public function __construct(StaffRepository $staffRepository)
    {
        $this->staffRepository = $staffRepository;
    }

    //Secretary--------------------------------------------------

    //Enrollment
    public function enrollStudent($data)
    {
        return DB::transaction(function () use ($data) {

            $this->staffRepository->updateUserRole($data['StudentId'], 'Student');

            return $this->staffRepository->createEnrollment($data);
        });
    }

    public function viewEnrolledStudentsInCourse($courseId)
    {
        return $this->staffRepository->getEnrolledStudentsInCourse($courseId);
    }

    //Add course
    public function createCourseWithSchedule($data)
    {
        return DB::transaction(function () use ($data) {

            $endDate = $this->staffRepository->calculateCourseEndDate(
                $data['Start_Date'],
                $data['CourseDays'],
                $data['Number_of_lessons']
            );

            // Check for schedule conflict before creating the course
            $conflict = $this->staffRepository->checkCourseScheduleConflict(
                $data['RoomId'],
                $data['Start_Date'],
                $endDate,  // or however you calculate it from lessons
                $data['CourseDays'],
                $data['Start_Time'],
                $data['End_Time']
            );


            if ($conflict) {
                return response()->json([
                    'Message' => 'The new course schedule conflicts with an existing course in the same room.'
                ], 400);
            }

            $course = $this->staffRepository->createCourse($data);

            $schedule = $this->staffRepository->createSchedule($course->id, [
                'RoomId' => $data['RoomId'],
                'Start_Enroll' => $data['Start_Enroll'],
                'End_Enroll' => $data['End_Enroll'],
                'Start_Date' => Carbon::parse($data['Start_Date'])->setTimeFromTimeString($data['Start_Time']),
                'End_Date' => $endDate,
                'Start_Time' => $data['Start_Time'],
                'End_Time' => $data['End_Time'],
                'CourseDays' => $data['CourseDays'],
            ]);

            $lessons = $this->generateLessons($course->id, $data['Start_Date'], $data['Start_Time'], $data['End_Time'], $data['Number_of_lessons'], $data['CourseDays']);

            Lesson::insert($lessons);

            return [
                'Course' => $course,
                'Schedule' => $schedule,
                'Lessons' => $lessons,
            ];
        });
    }

    private function generateLessons($courseId, $startDate, $startTime, $endTime, $lessonCount, $daysOfWeek)
    {
        $lessons = [];
        $date = Carbon::parse($startDate);
        $count = 0;

        while ($count < $lessonCount) {
            if (in_array($date->format('D'), $daysOfWeek)) {
                $lessons[] = [
                    'CourseId' => $courseId,
                    'Title' => "Lesson " . ($count + 1),
                    'Date' => $date->format('Y-m-d'),
                    'Start_Time' => $startTime,
                    'End_Time' => $endTime,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $count++;
            }
            $date->addDay();
        }

        return $lessons;
    }

    //Edit course
    public function editCourse($data)
    {
        return DB::transaction(function () use ($data) {

            $endDate = $this->staffRepository->calculateCourseEndDate(
                $data['Start_Date'],
                $data['CourseDays'],
                $data['Number_of_lessons']
            );

            $conflict = $this->staffRepository->checkCourseScheduleConflict(
                $data['RoomId'],
                $data['Start_Date'],
                $endDate,
                $data['CourseDays'],
                $data['Start_Time'],
                $data['End_Time']
            );


            if ($conflict) {
                return response()->json([
                    'Message' => 'The updated course schedule conflicts with an existing course in the same room.'
                ], 400);
            }

            // Update the schedule
            $this->staffRepository->updateCourseSchedule($data['CourseId'], [
                'RoomId' => $data['RoomId'],
                'Start_Enroll' => $data['Start_Enroll'],
                'End_Enroll' => $data['End_Enroll'],
                'Start_Date' => Carbon::parse($data['Start_Date'])->setTimeFromTimeString($data['Start_Time']),
                'End_Date' => $endDate,
                'Start_Time' => $data['Start_Time'],
                'End_Time' => $data['End_Time'],
                'CourseDays' => $data['CourseDays'],
            ]);

            // Delete old lessons
            Lesson::where('CourseId', $data['CourseId'])->delete();

            // Generate new lessons
            $lessons = $this->generateLessons(
                $data['CourseId'],
                $data['Start_Date'],
                $data['Start_Time'],
                $data['End_Time'],
                $data['Number_of_lessons'],
                $data['CourseDays']
            );

            Lesson::insert($lessons);

            return [
                'UpdatedSchedule' => true,
                'Lessons' => $lessons,
            ];
        });
    }

    //Teacher---------------------------------------------------------

    //Add flash card to lesson
    public function addFlashCard($data)
    {
        return DB::transaction(function () use ($data) {
            return $this->staffRepository->createFlashCard($data);
        });
    }

}

