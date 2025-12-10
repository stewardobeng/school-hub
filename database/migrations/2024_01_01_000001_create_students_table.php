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
        Schema::create('students', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email', 255)->unique();
            $table->string('phone', 20)->nullable();
            $table->string('grade', 20);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->date('enrollment_date');
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('parent_name', 200)->nullable();
            $table->string('parent_email', 255)->nullable();
            $table->string('parent_phone', 20)->nullable();
            $table->string('avatar_url', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

