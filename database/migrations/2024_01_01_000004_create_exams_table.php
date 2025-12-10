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
        Schema::create('exams', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('title', 255);
            $table->string('course_id', 20)->nullable();
            $table->string('grade', 20);
            $table->date('exam_date');
            $table->time('exam_time');
            $table->string('duration', 50);
            $table->enum('type', ['Midterm', 'Final', 'Quiz', 'Unit Test', 'Practical', 'Assessment']);
            $table->enum('status', ['Scheduled', 'In Progress', 'Completed', 'Cancelled'])->default('Scheduled');
            $table->integer('max_score')->default(100);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};

