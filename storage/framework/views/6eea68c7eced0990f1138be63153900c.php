<?php
    $adminLinks = [
        ['route' => 'admin.dashboard', 'icon' => 'LayoutDashboard', 'label' => 'Dashboard'],
        ['route' => 'admin.students.index', 'icon' => 'Users', 'label' => 'Students'],
        ['route' => 'admin.teachers.index', 'icon' => 'GraduationCap', 'label' => 'Teachers'],
        ['route' => 'admin.attendance.index', 'icon' => 'ClipboardCheck', 'label' => 'Attendance'],
        ['route' => 'admin.courses.index', 'icon' => 'BookOpen', 'label' => 'Courses'],
        ['route' => 'admin.exams.index', 'icon' => 'FileText', 'label' => 'Exam'],
        ['route' => 'admin.payments.index', 'icon' => 'CreditCard', 'label' => 'Payment'],
    ];
    
    $studentLinks = [
        ['route' => 'student.dashboard', 'icon' => 'LayoutDashboard', 'label' => 'Dashboard'],
        ['route' => 'student.courses', 'icon' => 'BookOpen', 'label' => 'My Courses'],
        ['route' => 'student.grades', 'icon' => 'FileText', 'label' => 'Grades'],
        ['route' => 'student.schedule', 'icon' => 'ClipboardCheck', 'label' => 'Schedule'],
        ['route' => 'student.assignments', 'icon' => 'FileText', 'label' => 'Assignments'],
    ];
    
    $teacherLinks = [
        ['route' => 'teacher.dashboard', 'icon' => 'LayoutDashboard', 'label' => 'Dashboard'],
        ['route' => 'teacher.classes', 'icon' => 'BookOpen', 'label' => 'My Classes'],
        ['route' => 'teacher.students', 'icon' => 'Users', 'label' => 'Students'],
        ['route' => 'teacher.attendance', 'icon' => 'ClipboardCheck', 'label' => 'Attendance'],
        ['route' => 'teacher.grading', 'icon' => 'FileText', 'label' => 'Grading'],
    ];
    
    $links = $userRole === 'admin' ? $adminLinks : ($userRole === 'student' ? $studentLinks : $teacherLinks);
    $currentPath = request()->path();
?>

<aside class="fixed left-0 top-0 z-40 h-screen w-60 bg-sidebar flex flex-col">
    <!-- Logo -->
    <div class="flex items-center gap-2 px-6 py-6">
        <div class="flex items-center justify-center w-8 h-8">
            <svg viewBox="0 0 24 24" class="w-8 h-8" fill="none">
                <circle cx="12" cy="8" r="3" fill="hsl(var(--gold))" />
                <circle cx="6" cy="16" r="2.5" fill="hsl(var(--gold))" />
                <circle cx="18" cy="16" r="2.5" fill="hsl(var(--gold))" />
                <path d="M12 11v3M8 14l2 1M16 14l-2 1" stroke="hsl(var(--gold))" stroke-width="1.5" />
            </svg>
        </div>
        <span class="text-xl font-bold text-primary-foreground">SCHOOL</span>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $linkUrl = isset($link['route']) ? route($link['route']) : url($link['to'] ?? '/');
                $isActive = request()->routeIs($link['route'] ?? '') || request()->is(ltrim(parse_url($linkUrl, PHP_URL_PATH), '/'));
            ?>
            <a href="<?php echo e($linkUrl); ?>" 
               class="sidebar-link <?php echo e($isActive ? 'active' : ''); ?>">
                <?php if($link['icon'] === 'LayoutDashboard'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <?php elseif($link['icon'] === 'Users'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <?php elseif($link['icon'] === 'GraduationCap'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7"></path></svg>
                <?php elseif($link['icon'] === 'ClipboardCheck'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                <?php elseif($link['icon'] === 'BookOpen'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <?php elseif($link['icon'] === 'FileText'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <?php elseif($link['icon'] === 'CreditCard'): ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                <?php endif; ?>
                <span><?php echo e($link['label']); ?></span>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>

    <!-- Bottom Actions -->
    <div class="px-3 py-4 border-t border-sidebar-border space-y-1">
        <a href="<?php echo e(route('settings')); ?>" class="sidebar-link">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span>Settings</span>
        </a>
        <a href="<?php echo e(route('home')); ?>" class="sidebar-link">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span>Logout</span>
        </a>
    </div>
</aside>

<?php /**PATH C:\xampp\htdocs\school-hub\resources\views/components/sidebar.blade.php ENDPATH**/ ?>