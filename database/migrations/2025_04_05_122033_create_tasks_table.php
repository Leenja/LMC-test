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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('AssigneeId')->constrained('usertasks');
            $table->foreignId('CreatorId')->constrained('usertasks');
            $table->string('Description');
            $table->enum('Status', ['Pending','Done'])->default('Pending');
            $table->dateTime('Deadline');  //'2025-04-05 14:30:00'
            $table->dateTime('Completed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
