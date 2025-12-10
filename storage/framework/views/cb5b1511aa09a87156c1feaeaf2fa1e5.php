

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="{ activeTab: 'overview' }">
    <!-- Back Button -->
    <a href="<?php echo e(route('admin.students.index')); ?>" class="inline-flex items-center gap-2 text-foreground hover:text-primary transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Students
    </a>

    <!-- Student Profile Header -->
    <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
        <div class="flex items-start gap-6">
            <div class="w-32 h-32 border-4 border-primary rounded-full bg-primary text-primary-foreground flex items-center justify-center text-4xl font-semibold">
                <?php echo e(substr($student->first_name, 0, 1)); ?><?php echo e(substr($student->last_name, 0, 1)); ?>

            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2"><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></h1>
                        <div class="flex items-center gap-4 text-muted-foreground">
                            <span class="font-mono text-sm">ID: <?php echo e($student->id); ?></span>
                            <span class="px-2 py-1 text-xs rounded-full <?php echo e($student->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($student->status); ?>

                            </span>
                        </div>
                    </div>
                    <a href="<?php echo e(route('admin.students.index')); ?>" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">Edit Student</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        </svg>
                        <span class="text-sm"><?php echo e($student->grade); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm"><?php echo e($student->email); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-sm"><?php echo e($student->phone ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm">Enrolled: <?php echo e(\Carbon\Carbon::parse($student->enrollment_date)->format('M d, Y')); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <?php
        $overallGPA = $examResults->count() > 0 ? $examResults->avg('score') / 100 * 4 : 0;
        $attendanceRate = $attendance->count() > 0 ? ($attendance->where('status', 'Present')->count() / $attendance->count()) * 100 : 0;
        $totalCredits = $courses->sum('credits') ?? 0;
    ?>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-card rounded-xl p-4 shadow-sm border border-border/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Overall GPA</p>
                    <p class="text-2xl font-bold"><?php echo e(number_format($overallGPA, 2)); ?></p>
                </div>
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
            </div>
        </div>
        <div class="bg-card rounded-xl p-4 shadow-sm border border-border/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Attendance</p>
                    <p class="text-2xl font-bold"><?php echo e(number_format($attendanceRate, 1)); ?>%</p>
                </div>
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
        </div>
        <div class="bg-card rounded-xl p-4 shadow-sm border border-border/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Total Credits</p>
                    <p class="text-2xl font-bold"><?php echo e($totalCredits); ?></p>
                </div>
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
        <div class="bg-card rounded-xl p-4 shadow-sm border border-border/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Active Courses</p>
                    <p class="text-2xl font-bold"><?php echo e($courses->count()); ?></p>
                </div>
                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-border">
        <div class="flex gap-4">
            <button @click="activeTab = 'overview'" 
                    :class="activeTab === 'overview' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">Overview</button>
            <button @click="activeTab = 'courses'" 
                    :class="activeTab === 'courses' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">Courses</button>
            <button @click="activeTab = 'grades'" 
                    :class="activeTab === 'grades' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">Grades & Exams</button>
            <button @click="activeTab = 'attendance'" 
                    :class="activeTab === 'attendance' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">Attendance</button>
            <button @click="activeTab = 'payments'" 
                    :class="activeTab === 'payments' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">Payments</button>
        </div>
    </div>

    <!-- Overview Tab -->
    <div x-show="activeTab === 'overview'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Personal Information
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-muted-foreground">Enrollment Date</p>
                            <p class="font-medium"><?php echo e(\Carbon\Carbon::parse($student->enrollment_date)->format('M d, Y')); ?></p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Grade</p>
                            <p class="font-medium"><?php echo e($student->grade); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Tab -->
    <div x-show="activeTab === 'courses'" class="space-y-6">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Enrolled Courses
            </h3>
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Course Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Course Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Teacher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Credits</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded border font-mono"><?php echo e($course->code ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-6 py-4 font-medium"><?php echo e($course->name ?? 'N/A'); ?></td>
                                <td class="px-6 py-4"><?php echo e($course->teacher_name ?? 'N/A'); ?></td>
                                <td class="px-6 py-4"><?php echo e($course->credits ?? 0); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-muted-foreground">No courses enrolled</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grades & Exams Tab -->
    <div x-show="activeTab === 'grades'" class="space-y-6">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
                Exam Results
            </h3>
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Exam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Grade</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php $__empty_1 = true; $__currentLoopData = $examResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4 font-medium"><?php echo e($result->title ?? 'N/A'); ?></td>
                                <td class="px-6 py-4"><?php echo e($result->exam_date ? \Carbon\Carbon::parse($result->exam_date)->format('M d, Y') : 'N/A'); ?></td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold"><?php echo e($result->score ?? 0); ?>/<?php echo e($result->max_score ?? 100); ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                        $grade = 'N/A';
                                        if ($result->score && $result->max_score) {
                                            $percentage = ($result->score / $result->max_score) * 100;
                                            if ($percentage >= 90) $grade = 'A';
                                            elseif ($percentage >= 80) $grade = 'B';
                                            elseif ($percentage >= 70) $grade = 'C';
                                            elseif ($percentage >= 60) $grade = 'D';
                                            else $grade = 'F';
                                        }
                                    ?>
                                    <span class="px-2 py-1 text-xs rounded <?php echo e($grade === 'A' ? 'bg-green-100 text-green-800' : 'bg-muted'); ?>"><?php echo e($grade); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-muted-foreground">No exam results</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Attendance Tab -->
    <div x-show="activeTab === 'attendance'" class="space-y-6">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                Attendance Record
            </h3>
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Class</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php $__empty_1 = true; $__currentLoopData = $attendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4 font-medium"><?php echo e($record->attendance_date ? \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') : 'N/A'); ?></td>
                                <td class="px-6 py-4"><?php echo e($record->class_name ?? 'N/A'); ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded <?php echo e($record->status === 'Present' ? 'bg-green-100 text-green-800' : ($record->status === 'Late' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800')); ?>">
                                        <?php echo e($record->status ?? 'N/A'); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-muted-foreground">No attendance records</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Payments Tab -->
    <div x-show="activeTab === 'payments'" class="space-y-6">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Payment History
            </h3>
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Payment ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-6 py-4 font-mono text-sm"><?php echo e($payment->id); ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded border"><?php echo e($payment->payment_type ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-green-600">$<?php echo e(number_format($payment->amount ?? 0, 2)); ?></td>
                                <td class="px-6 py-4"><?php echo e($payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A'); ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800"><?php echo e($payment->status ?? 'Paid'); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-muted-foreground">No payment records</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>
[x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', ['title' => 'Student Details', 'userRole' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\school-hub\resources\views/admin/students/show.blade.php ENDPATH**/ ?>