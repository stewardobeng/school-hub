<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'School Hub API is running'
    ]);
});

// Dashboard
Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats']);

// Students API
Route::prefix('students')->group(function () {
    Route::get('/', [AdminStudentController::class, 'index']);
    Route::get('/{id}', [AdminStudentController::class, 'show']);
    Route::post('/', [AdminStudentController::class, 'store']);
    Route::put('/{id}', [AdminStudentController::class, 'update']);
    Route::delete('/{id}', [AdminStudentController::class, 'destroy']);
});

// Teachers API
Route::prefix('teachers')->group(function () {
    Route::get('/', [AdminTeacherController::class, 'index']);
    Route::get('/{id}', [AdminTeacherController::class, 'show']);
    Route::post('/', [AdminTeacherController::class, 'store']);
    Route::put('/{id}', [AdminTeacherController::class, 'update']);
    Route::delete('/{id}', [AdminTeacherController::class, 'destroy']);
});

// Courses API
Route::prefix('courses')->group(function () {
    Route::get('/', [AdminCourseController::class, 'index']);
    Route::get('/{id}', [AdminCourseController::class, 'show']);
    Route::post('/', [AdminCourseController::class, 'store']);
    Route::put('/{id}', [AdminCourseController::class, 'update']);
    Route::delete('/{id}', [AdminCourseController::class, 'destroy']);
});

// Exams API
Route::prefix('exams')->group(function () {
    Route::get('/', [AdminExamController::class, 'index']);
    Route::get('/{id}', [AdminExamController::class, 'show']);
    Route::post('/', [AdminExamController::class, 'store']);
    Route::put('/{id}', [AdminExamController::class, 'update']);
    Route::delete('/{id}', [AdminExamController::class, 'destroy']);
});

// Payments API
Route::prefix('payments')->group(function () {
    Route::get('/', [AdminPaymentController::class, 'index']);
    Route::get('/{id}', [AdminPaymentController::class, 'show']);
    Route::post('/', [AdminPaymentController::class, 'store']);
    Route::put('/{id}', [AdminPaymentController::class, 'update']);
    Route::delete('/{id}', [AdminPaymentController::class, 'destroy']);
});

// Attendance API
Route::prefix('attendance')->group(function () {
    Route::get('/', [AdminAttendanceController::class, 'index']);
    Route::get('/{id}', [AdminAttendanceController::class, 'show']);
    Route::post('/', [AdminAttendanceController::class, 'store']);
    Route::put('/{id}', [AdminAttendanceController::class, 'update']);
    Route::delete('/{id}', [AdminAttendanceController::class, 'destroy']);
});

