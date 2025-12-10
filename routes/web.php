<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\TeacherController as AdminTeacherController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\ExamController as AdminExamController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;

// Home/Index route
Route::get('/', function () {
    return view('index');
})->name('home');

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');
    
    // Students
    Route::get('/students', [AdminStudentController::class, 'index'])->name('admin.students.index');
    Route::get('/students/{id}', [AdminStudentController::class, 'show'])->name('admin.students.show');
    
    // Teachers
    Route::get('/teachers', [AdminTeacherController::class, 'index'])->name('admin.teachers.index');
    Route::get('/teachers/{id}', [AdminTeacherController::class, 'show'])->name('admin.teachers.show');
    
    // Courses
    Route::get('/courses', [AdminCourseController::class, 'index'])->name('admin.courses.index');
    
    // Exams
    Route::get('/exam', [AdminExamController::class, 'index'])->name('admin.exams.index');
    
    // Payments
    Route::get('/payment', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
    
    // Attendance
    Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('admin.attendance.index');
    
    // Settings & Profile
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
    
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');
});

// Student Routes
Route::prefix('student')->group(function () {
    Route::get('/', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
    
    Route::get('/courses', function () {
        return view('student.dashboard');
    })->name('student.courses');
    
    Route::get('/grades', function () {
        return view('student.dashboard');
    })->name('student.grades');
    
    Route::get('/schedule', function () {
        return view('student.dashboard');
    })->name('student.schedule');
    
    Route::get('/assignments', function () {
        return view('student.dashboard');
    })->name('student.assignments');
});

// Teacher Routes
Route::prefix('teacher')->group(function () {
    Route::get('/', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');
    
    Route::get('/classes', function () {
        return view('teacher.dashboard');
    })->name('teacher.classes');
    
    Route::get('/students', function () {
        return view('teacher.dashboard');
    })->name('teacher.students');
    
    Route::get('/attendance', function () {
        return view('teacher.dashboard');
    })->name('teacher.attendance');
    
    Route::get('/grading', function () {
        return view('teacher.dashboard');
    })->name('teacher.grading');
});

// Settings route (global)
Route::get('/settings', function () {
    return view('admin.settings');
})->name('settings');

// 404 - Catch all unmatched routes
Route::fallback(function () {
    return view('404');
});

