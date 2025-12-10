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
        Schema::create('attendance', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('class_name', 255);
            $table->string('teacher_id', 20)->nullable();
            $table->date('attendance_date');
            $table->integer('total_students');
            $table->integer('present');
            $table->integer('absent');
            $table->integer('late')->default(0);
            $table->enum('status', ['Completed', 'Pending'])->default('Pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};

