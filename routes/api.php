<?php

use App\Http\Controllers\StaffController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post("enroll", [StaffController::class, "enrollStudent"]);

Route::post("addCourse", [StaffController::class, "addCourse"]);

Route::post("editCourse", [StaffController::class,"editCourse"]);
