<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courseschedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('CourseId')->constrained('courses');
            $table->foreignId('RoomId')->constrained('rooms');
            $table->date('Start_Enroll');
            $table->date('End_Enroll');
            $table->date('Start_Date');
            $table->date('End_Date');
            $table->time('Start_Time');
            $table->time('End_Time');
            $table->json('CourseDays');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courseschedules');
    }
};
