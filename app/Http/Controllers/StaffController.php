<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StaffService;
use Carbon\Carbon;


class StaffController extends Controller
{
    protected $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
    }

    //Logistic---------------------------------------------------
    public function addInvoice(Request $request) {

    }

    public function markCompletedTasks(Request $request) {

    }

    //Secretary--------------------------------------------------
    public function enrollStudent (Request $request) {
        $data = $request->validate([
            'StudentId' => 'required|exists:users,id',
            'CourseId' => 'required|exists:courses,id',
            'isPrivate' => 'required|boolean',
        ]);

        return response()->json(
            $this->staffService->enrollStudent($data)
        );
    }

    public function addCourse(Request $request) {

        $data = $request->validate([
            'TeacherId' => 'required|exists:users,id',
            'LanguageId' => 'required|exists:languages,id',
            'RoomId' => 'required|exists:rooms,id',
            'Description' => 'required|string',
            'Level' => 'required|string',
            'Start_Enroll' => 'required|date|after_or_equal:now()|before_or_equal:End_Enroll',
            'End_Enroll' => 'required|date|after_or_equal:now()|after_or_equal:Start_Enroll',
            'Start_Date' => 'required|date|after_or_equal:now()|after:Start_Enroll|after:End_Enroll',
            'Start_Time' => 'required|date_format:H:i',
            'End_Time' => 'required|date_format:H:i|after:Start_Time',
            'Number_of_lessons' => 'required|integer|min:1',
            'CourseDays' => 'required|array|min:1',
            'CourseDays.*' => 'in:Sun,Mon,Tue,Wed,Thu,Fri,Sat',
        ]);

        // Check if the Start_Date matches any of the CourseDays
        $startDate = Carbon::parse($data['Start_Date']);
        $courseDays = $data['CourseDays'];
        $startDayOfWeek = $startDate->format('D'); // Get the day of the week for Start_Date

        if (!in_array($startDayOfWeek, $courseDays)) {
            return response()->json([
                'error' => "The Start Date doesn't match the selected Course Days. Please adjust the Start Date to match one of the selected days."
            ], 400);
        }

        return response()->json(
            $this->staffService->createCourseWithSchedule($data)
        );
    }

    public function editCourse(Request $request) {
        $data = $request->validate([
            'CourseId' => 'required|exists:courses,id',
            'Start_Enroll' => 'required|date|after_or_equal:now()|before_or_equal:End_Enroll',
            'End_Enroll' => 'required|date|after_or_equal:now()|after_or_equal:Start_Enroll',
            'Start_Date' => 'required|date|after_or_equal:now()|after:Start_Enroll|after:End_Enroll',
            'Start_Time' => 'required|date_format:H:i',
            'End_Time' => 'required|date_format:H:i|after:Start_Time',
            'Number_of_lessons' => 'required|integer|min:1',
            'CourseDays' => 'required|array|min:1',
            'CourseDays.*' => 'in:Sun,Mon,Tue,Wed,Thu,Fri,Sat',
        ]);

        // Check if the Start_Date matches any of the CourseDays
        $startDate = Carbon::parse($data['Start_Date']);
        $courseDays = $data['CourseDays'];
        $startDayOfWeek = $startDate->format('D'); // Get the day of the week for Start_Date

        if (!in_array($startDayOfWeek, $courseDays)) {
            return response()->json([
                'error' => "The Start Date doesn't match the selected Course Days. Please adjust the Start Date to match one of the selected days."
            ], 400);
        }

        return response()->json(
            $this->staffService->editCourse($data)
        );
    }

    public function viewEnrolledStudents() {

    }
    public function reviewRoomReservations (Request $request) {

    }

    public function addAnnouncement (Request $request) {

    }

    public function viewInvoices () {

    }

    //Teacher---------------------------------------------------
    public function sendAssignments() {

    }

    public function reviewMyCourses() {

    }

    public function reviewSchedule() {

    }

    public function reviewStudentsNames() {

    }

    public function enterMark(Request $request) {

    }

    public function addTest(Request $request) {

    }

    public function checkAttendance() {

    }

    public function addFlashCard(Request $request) {

    }

    public function editFlashCard(Request $request) {

    }

    public function requestPrivateCourse() {

    }

    public function submitComplaint(Request $request) {

    }
}
