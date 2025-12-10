

<?php $__env->startSection('content'); ?>
<?php
    $stats = $dashboardData['stats'] ?? [];
    $earningsData = $dashboardData['earningsData'] ?? [];
    $topPerformers = $dashboardData['topPerformers'] ?? [];
    $upcomingExams = $dashboardData['upcomingExams'] ?? [];
    $attendanceSummary = $dashboardData['attendanceSummary'] ?? [];
    
    // Calculate attendance percentages
    $totalAttendance = 0;
    $totalPresent = 0;
    foreach ($attendanceSummary as $day) {
        $totalAttendance += $day->total ?? 0;
        $totalPresent += $day->present ?? 0;
    }
    $studentsPercentage = $totalAttendance > 0 ? round(($totalPresent / $totalAttendance) * 100) : 0;
    $teachersPercentage = $studentsPercentage > 0 ? min($studentsPercentage + 7, 100) : 0;
    
    // Format earnings
    $formatEarnings = function($amount) {
        if ($amount >= 1000000) {
            return '$' . number_format($amount / 1000000, 1) . 'M';
        } else if ($amount >= 1000) {
            return '$' . number_format($amount / 1000, 1) . 'k';
        }
        return '$' . number_format($amount, 0);
    };
?>

<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-blue-100">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Students</p>
            <p class="text-2xl font-bold text-foreground"><?php echo e(number_format($stats['totalStudents'] ?? 0)); ?></p>
        </div>
    </div>

    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-purple-100">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Teachers</p>
            <p class="text-2xl font-bold text-foreground"><?php echo e(number_format($stats['totalTeachers'] ?? 0)); ?></p>
        </div>
    </div>

    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-green-100">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Parents</p>
            <p class="text-2xl font-bold text-foreground"><?php echo e(number_format($stats['totalParents'] ?? 0)); ?></p>
        </div>
    </div>

    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-amber-100">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Earnings</p>
            <p class="text-2xl font-bold text-foreground"><?php echo e($formatEarnings($stats['totalPayments'] ?? 0)); ?></p>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column - Chart -->
    <div class="lg:col-span-2">
        <div class="chart-card">
            <h3 class="text-lg font-semibold mb-4">Earnings Overview</h3>
            <canvas id="earningsChart" height="300"></canvas>
        </div>
    </div>

    <!-- Right Column - Calendar -->
    <div>
        <div class="chart-card">
            <h3 class="text-lg font-semibold mb-4">Upcoming Exams</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $upcomingExams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 bg-muted rounded-lg">
                        <p class="font-medium text-sm"><?php echo e($exam->title ?? $exam['title'] ?? 'N/A'); ?></p>
                        <p class="text-xs text-muted-foreground mt-1">
                            <?php echo e(\Carbon\Carbon::parse($exam->exam_date ?? $exam['exam_date'] ?? now())->format('M d, Y')); ?>

                        </p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-muted-foreground">No upcoming exams</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Bottom Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <!-- Top Performers -->
    <div class="lg:col-span-1">
        <div class="chart-card">
            <h3 class="text-lg font-semibold mb-4">Top Performers</h3>
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $topPerformers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $performer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-2">
                        <div>
                            <p class="font-medium text-sm"><?php echo e(($performer->first_name ?? $performer['first_name'] ?? '') . ' ' . ($performer->last_name ?? $performer['last_name'] ?? '')); ?></p>
                            <p class="text-xs text-muted-foreground"><?php echo e($performer->grade ?? $performer['grade'] ?? 'N/A'); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-sm"><?php echo e(number_format($performer->avg_score ?? $performer['avg_score'] ?? 0, 1)); ?>%</p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-muted-foreground">No data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Attendance -->
    <div>
        <div class="chart-card">
            <h3 class="text-lg font-semibold mb-4">Attendance</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>Students</span>
                        <span><?php echo e($studentsPercentage); ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo e($studentsPercentage); ?>%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span>Teachers</span>
                        <span><?php echo e($teachersPercentage); ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?php echo e($teachersPercentage); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Community Card -->
    <div>
        <div class="chart-card">
            <h3 class="text-lg font-semibold mb-4">Community</h3>
            <p class="text-sm text-muted-foreground">Welcome to School Hub! Manage your school efficiently.</p>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const earningsData = <?php echo json_encode($earningsData, 15, 512) ?>;
        const ctx = document.getElementById('earningsChart');
        
        if (ctx && earningsData.length > 0) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: earningsData.map(item => item.month_name ?? item['month_name'] ?? ''),
                    datasets: [{
                        label: 'Earnings',
                        data: earningsData.map(item => item.earnings ?? item['earnings'] ?? 0),
                        borderColor: 'hsl(var(--primary))',
                        backgroundColor: 'hsl(var(--primary) / 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', ['title' => 'Dashboard', 'userRole' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\school-hub\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>