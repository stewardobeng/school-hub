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
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->string('exam_id', 20);
            $table->string('student_id', 20);
            $table->decimal('score', 5, 2);
            $table->integer('max_score')->default(100);
            $table->string('grade', 10)->nullable();
            $table->enum('status', ['Completed', 'Absent', 'Pending'])->default('Pending');
            $table->timestamps();

            $table->foreign('exam_id')->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->unique(['exam_id', 'student_id'], 'unique_result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};

