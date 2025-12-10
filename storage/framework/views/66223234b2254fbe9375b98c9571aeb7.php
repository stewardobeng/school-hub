<header class="sticky top-0 z-50 flex items-center justify-between h-16 px-6 bg-card/95 backdrop-blur supports-[backdrop-filter]:bg-card/80 border-b border-border">
    <h1 class="text-2xl font-semibold text-foreground"><?php echo e($title ?? 'Dashboard'); ?></h1>
    
    <div class="flex items-center gap-4">
        <!-- Search -->
        <div class="relative w-80">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input
                type="search"
                placeholder="Search for students/teachers/documents..."
                class="w-full pl-10 pr-4 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring"
            />
        </div>

        <!-- Notifications -->
        <button class="relative p-2 rounded-full hover:bg-muted transition-colors">
            <svg class="w-5 h-5 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute top-1 right-1 w-2 h-2 bg-accent rounded-full"></span>
        </button>

        <!-- User Avatar -->
        <a href="<?php echo e(route('admin.profile')); ?>">
            <div class="w-10 h-10 border-2 border-accent rounded-full cursor-pointer hover:opacity-80 transition-opacity bg-primary text-primary-foreground flex items-center justify-center font-semibold">
                JD
            </div>
        </a>
    </div>
</header>

<?php /**PATH C:\xampp\htdocs\school-hub\resources\views/components/header.blade.php ENDPATH**/ ?>