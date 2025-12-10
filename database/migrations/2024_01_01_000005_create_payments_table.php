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
        Schema::create('payments', function (Blueprint $table) {
            $table->string('id', 20)->primary();
            $table->string('student_id', 20);
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['Tuition Fee', 'Library Fee', 'Exam Fee', 'Other']);
            $table->enum('method', ['Credit Card', 'Bank Transfer', 'Cash', 'Online Payment']);
            $table->date('payment_date');
            $table->enum('status', ['Paid', 'Pending', 'Failed', 'Refunded'])->default('Pending');
            $table->string('transaction_id', 100)->unique()->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

