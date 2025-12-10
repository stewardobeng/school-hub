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
        Schema::create('courses', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('name', 255);
            $table->string('code', 50)->unique();
            $table->string('grade', 20);
            $table->string('teacher_id', 20)->nullable();
            $table->integer('credits');
            $table->string('duration', 50);
            $table->text('schedule')->nullable();
            $table->enum('status', ['Active', 'Archived'])->default('Active');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

