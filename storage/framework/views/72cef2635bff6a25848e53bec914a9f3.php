

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="courseManagement()">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Total Courses</p>
                <p class="text-2xl font-bold text-foreground"><?php echo e($courses->count()); ?></p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-green-100">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Active Courses</p>
                <p class="text-2xl font-bold text-foreground"><?php echo e($courses->where('status', 'Active')->count()); ?></p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-purple-100">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Total Enrollments</p>
                <p class="text-2xl font-bold text-foreground"><?php echo e($courses->sum('student_count')); ?></p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-card rounded-xl shadow-sm border border-border/50">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold">Course Management</h2>
                    <p class="text-sm text-muted-foreground mt-1">Manage courses and enrollments</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchCourses()" placeholder="Search courses..." 
                               class="pl-10 pr-4 py-2 w-64 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <button @click="openAddDialog()" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Course
                        </span>
                    </button>
                </div>
            </div>
            
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Teacher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Credits</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Students</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-muted-foreground uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php $__empty_1 = true; $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-muted/50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium"><?php echo e($course->name ?? 'N/A'); ?></div>
                                        <div class="text-sm text-muted-foreground mt-1"><?php echo e($course->description ?? 'No description'); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded border font-mono"><?php echo e($course->code ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm"><?php echo e($course->grade ?? 'N/A'); ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="text-sm"><?php echo e(($course->teacher_first_name ?? '') . ' ' . ($course->teacher_last_name ?? 'N/A')); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded bg-muted"><?php echo e($course->credits ?? 0); ?> credits</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                        </svg>
                                        <span class="font-medium"><?php echo e($course->student_count ?? 0); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($course->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-muted'); ?>">
                                        <?php echo e($course->status ?? 'Active'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditDialog('<?php echo e($course->id); ?>')" class="text-primary hover:text-primary/80">Edit</button>
                                        <button @click="openDeleteDialog('<?php echo e($course->id); ?>')" class="text-destructive hover:text-destructive/80">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-muted-foreground">No courses found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showAddModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Add New Course</h3>
                    <button @click="showAddModal = false" class="text-muted-foreground hover:text-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="handleAddSubmit()" class="space-y-4">
                    <div x-show="errorMessage" class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" x-text="errorMessage"></div>
                    <div x-show="successMessage" class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm" x-text="successMessage"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Course Name *</label>
                            <input type="text" x-model="formData.name" required placeholder="Mathematics - Algebra" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Course Code *</label>
                            <input type="text" x-model="formData.code" required placeholder="MATH-101" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Grade *</label>
                            <select x-model="formData.grade" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select grade</option>
                                <option value="Grade 9">Grade 9</option>
                                <option value="Grade 10">Grade 10</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Teacher *</label>
                            <select x-model="formData.teacher_id" class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select teacher</option>
                                <?php $__currentLoopData = \App\Models\Teacher::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Credits *</label>
                            <input type="number" x-model="formData.credits" required placeholder="3" min="1" max="6" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Duration *</label>
                            <input type="text" x-model="formData.duration" required placeholder="1 semester" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Description</label>
                        <textarea x-model="formData.description" rows="3" placeholder="Course description..." 
                                  class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                    </div>
                    <div class="flex gap-3 justify-end pt-4">
                        <button type="button" @click="showAddModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span x-show="!isLoading">Add Course</span>
                            <span x-show="isLoading">Adding...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showEditModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Edit Course</h3>
                    <button @click="showEditModal = false" class="text-muted-foreground hover:text-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="handleEditSubmit()" class="space-y-4">
                    <div x-show="errorMessage" class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" x-text="errorMessage"></div>
                    <div x-show="successMessage" class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm" x-text="successMessage"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Course Name *</label>
                            <input type="text" x-model="formData.name" required placeholder="Mathematics - Algebra" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Course Code *</label>
                            <input type="text" x-model="formData.code" required placeholder="MATH-101" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Grade *</label>
                            <select x-model="formData.grade" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select grade</option>
                                <option value="Grade 9">Grade 9</option>
                                <option value="Grade 10">Grade 10</option>
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Teacher *</label>
                            <select x-model="formData.teacher_id" class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select teacher</option>
                                <?php $__currentLoopData = \App\Models\Teacher::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Credits *</label>
                            <input type="number" x-model="formData.credits" required placeholder="3" min="1" max="6" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Duration *</label>
                            <input type="text" x-model="formData.duration" required placeholder="1 semester" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Status *</label>
                            <select x-model="formData.status" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="Active">Active</option>
                                <option value="Archived">Archived</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Description</label>
                        <textarea x-model="formData.description" rows="3" placeholder="Course description..." 
                                  class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring"></textarea>
                    </div>
                    <div class="flex gap-3 justify-end pt-4">
                        <button type="button" @click="showEditModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span x-show="!isLoading">Update Course</span>
                            <span x-show="isLoading">Updating...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showDeleteModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-md w-full mx-4">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-2">Delete Course?</h3>
                <p class="text-sm text-muted-foreground mb-6">This will permanently delete the course and remove all associated enrollments. This action cannot be undone.</p>
                <div x-show="errorMessage" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" x-text="errorMessage"></div>
                <div class="flex gap-3 justify-end">
                    <button @click="showDeleteModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                    <button @click="handleDelete()" :disabled="isLoading" class="bg-destructive text-destructive-foreground px-4 py-2 rounded-md hover:bg-destructive/90 transition-colors disabled:opacity-50">
                        <span x-show="!isLoading">Delete</span>
                        <span x-show="isLoading">Deleting...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function courseManagement() {
    return {
        searchQuery: '',
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        selectedCourseId: null,
        formData: {
            name: '',
            code: '',
            grade: '',
            teacher_id: '',
            credits: '',
            duration: '',
            description: '',
            status: 'Active'
        },
        courses: <?php echo json_encode($courses, 15, 512) ?>,
        filteredCourses: <?php echo json_encode($courses, 15, 512) ?>,
        isLoading: false,
        errorMessage: '',
        successMessage: '',
        openAddDialog() {
            this.resetForm();
            this.showAddModal = true;
            this.errorMessage = '';
            this.successMessage = '';
        },
        async openEditDialog(courseId) {
            this.isLoading = true;
            this.errorMessage = '';
            this.successMessage = '';
            try {
                const response = await fetch(`/api/courses/${courseId}`);
                const data = await response.json();
                if (data.success) {
                    this.selectedCourseId = courseId;
                    this.formData = {
                        name: data.data.name,
                        code: data.data.code,
                        grade: data.data.grade,
                        teacher_id: data.data.teacher_id || '',
                        credits: data.data.credits.toString(),
                        duration: data.data.duration,
                        description: data.data.description || '',
                        status: data.data.status
                    };
                    this.showEditModal = true;
                } else {
                    this.errorMessage = 'Failed to load course data';
                }
            } catch (error) {
                this.errorMessage = 'Error loading course data';
                console.error('Error:', error);
            } finally {
                this.isLoading = false;
            }
        },
        openDeleteDialog(courseId) {
            this.selectedCourseId = courseId;
            this.showDeleteModal = true;
        },
        async handleAddSubmit() {
            this.isLoading = true;
            this.errorMessage = '';
            this.successMessage = '';
            try {
                const response = await fetch('/api/courses', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: 'CRS' + String(Date.now()).slice(-6),
                        ...this.formData,
                        credits: parseInt(this.formData.credits)
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.successMessage = 'Course added successfully!';
                    this.showAddModal = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to add course');
                    this.isLoading = false;
                }
            } catch (error) {
                this.errorMessage = 'An error occurred. Please try again.';
                this.isLoading = false;
                console.error('Error:', error);
            }
        },
        async handleEditSubmit() {
            this.isLoading = true;
            this.errorMessage = '';
            this.successMessage = '';
            try {
                const response = await fetch(`/api/courses/${this.selectedCourseId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ...this.formData,
                        credits: parseInt(this.formData.credits)
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.successMessage = 'Course updated successfully!';
                    this.showEditModal = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to update course');
                    this.isLoading = false;
                }
            } catch (error) {
                this.errorMessage = 'An error occurred. Please try again.';
                this.isLoading = false;
                console.error('Error:', error);
            }
        },
        async handleDelete() {
            this.isLoading = true;
            this.errorMessage = '';
            try {
                const response = await fetch(`/api/courses/${this.selectedCourseId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.showDeleteModal = false;
                    window.location.reload();
                } else {
                    this.errorMessage = data.message || 'Failed to delete course';
                    this.isLoading = false;
                }
            } catch (error) {
                this.errorMessage = 'An error occurred. Please try again.';
                this.isLoading = false;
                console.error('Error:', error);
            }
        },
        fetchCourses() {
            const query = this.searchQuery.toLowerCase().trim();
            if (!query) {
                this.filteredCourses = this.courses;
                return;
            }
            this.filteredCourses = this.courses.filter(course => {
                const name = (course.name || '').toLowerCase();
                const code = (course.code || '').toLowerCase();
                return name.includes(query) || code.includes(query);
            });
        },
        resetForm() {
            this.formData = {
                name: '',
                code: '',
                grade: '',
                teacher_id: '',
                credits: '',
                duration: '',
                description: '',
                status: 'Active'
            };
        }
    }
}
</script>
<style>
[x-cloak] { display: none !important; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', ['title' => 'Courses', 'userRole' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\school-hub\resources\views/admin/courses/index.blade.php ENDPATH**/ ?>