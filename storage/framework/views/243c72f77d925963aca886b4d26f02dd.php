

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="examManagement()">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Total Exams</p>
                <p class="text-2xl font-bold text-foreground"><?php echo e($exams->count()); ?></p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-purple-100">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Upcoming</p>
                <p class="text-2xl font-bold text-foreground"><?php echo e($exams->where('status', 'Scheduled')->count()); ?></p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-green-100">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Completed</p>
                <p class="text-2xl font-bold text-foreground"><?php echo e($exams->where('status', 'Completed')->count()); ?></p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-card rounded-xl shadow-sm border border-border/50">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold">Exam Management</h2>
                    <p class="text-sm text-muted-foreground mt-1">Schedule and manage examinations</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchExams()" placeholder="Search exams..." 
                               class="pl-10 pr-4 py-2 w-64 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <select x-model="statusFilter" @change="fetchExams()" class="w-40 px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        <option value="all">All Status</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="in progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                    <button @click="openAddDialog()" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Schedule Exam
                        </span>
                    </button>
                </div>
            </div>
            
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Exam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-muted-foreground uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-muted/50">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium"><?php echo e($exam->title); ?></div>
                                        <div class="text-sm text-muted-foreground mt-1">ID: <?php echo e($exam->id); ?></div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded border font-mono"><?php echo e($exam->course_code ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm"><?php echo e($exam->grade); ?></td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium"><?php echo e($exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->format('M d, Y') : 'N/A'); ?></span>
                                        </div>
                                        <div class="flex items-center gap-2 mt-1 text-sm text-muted-foreground">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <?php echo e($exam->exam_time ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded bg-muted"><?php echo e($exam->duration ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded border"><?php echo e($exam->type ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($exam->status === 'Completed' ? 'bg-green-100 text-green-800' : ($exam->status === 'In Progress' ? 'bg-blue-100 text-blue-800' : 'bg-muted')); ?>">
                                        <?php echo e($exam->status ?? 'Scheduled'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditDialog('<?php echo e($exam->id); ?>')" class="text-primary hover:text-primary/80">Edit</button>
                                        <button @click="openDeleteDialog('<?php echo e($exam->id); ?>')" class="text-destructive hover:text-destructive/80">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-muted-foreground">No exams found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Exam Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showAddModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Schedule New Exam</h3>
                    <button @click="showAddModal = false" class="text-muted-foreground hover:text-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="handleAddSubmit()" class="space-y-4">
                    <div x-show="errorMessage" class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" x-text="errorMessage"></div>
                    <div x-show="successMessage" class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm" x-text="successMessage"></div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Exam Title *</label>
                        <input type="text" x-model="formData.title" required placeholder="Midterm Exam - Mathematics" 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Course *</label>
                            <select x-model="formData.course_id" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select course</option>
                                <?php $__currentLoopData = \App\Models\Course::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>"><?php echo e($course->code); ?> - <?php echo e($course->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
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
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Date *</label>
                            <input type="date" x-model="formData.exam_date" required 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Time *</label>
                            <input type="time" x-model="formData.exam_time" required 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Duration *</label>
                            <input type="text" x-model="formData.duration" required placeholder="2 hours" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Exam Type *</label>
                            <select x-model="formData.type" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select type</option>
                                <option value="Midterm">Midterm</option>
                                <option value="Final">Final</option>
                                <option value="Quiz">Quiz</option>
                                <option value="Unit Test">Unit Test</option>
                                <option value="Practical">Practical</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end pt-4">
                        <button type="button" @click="showAddModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span x-show="!isLoading">Schedule Exam</span>
                            <span x-show="isLoading">Scheduling...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Exam Modal -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showEditModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Edit Exam</h3>
                    <button @click="showEditModal = false" class="text-muted-foreground hover:text-foreground">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="handleEditSubmit()" class="space-y-4">
                    <div x-show="errorMessage" class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" x-text="errorMessage"></div>
                    <div x-show="successMessage" class="p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm" x-text="successMessage"></div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Exam Title *</label>
                        <input type="text" x-model="formData.title" required placeholder="Midterm Exam - Mathematics" 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Course *</label>
                            <select x-model="formData.course_id" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select course</option>
                                <?php $__currentLoopData = \App\Models\Course::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($course->id); ?>"><?php echo e($course->code); ?> - <?php echo e($course->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
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
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Date *</label>
                            <input type="date" x-model="formData.exam_date" required 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Time *</label>
                            <input type="time" x-model="formData.exam_time" required 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Status *</label>
                            <select x-model="formData.status" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="Scheduled">Scheduled</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Duration *</label>
                            <input type="text" x-model="formData.duration" required placeholder="2 hours" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Exam Type *</label>
                            <select x-model="formData.type" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select type</option>
                                <option value="Midterm">Midterm</option>
                                <option value="Final">Final</option>
                                <option value="Quiz">Quiz</option>
                                <option value="Unit Test">Unit Test</option>
                                <option value="Practical">Practical</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end pt-4">
                        <button type="button" @click="showEditModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span x-show="!isLoading">Update Exam</span>
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
                <h3 class="text-lg font-semibold mb-2">Cancel Exam?</h3>
                <p class="text-sm text-muted-foreground mb-6">This will cancel the exam. This action cannot be undone.</p>
                <div x-show="errorMessage" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" x-text="errorMessage"></div>
                <div class="flex gap-3 justify-end">
                    <button @click="showDeleteModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Keep Scheduled</button>
                    <button @click="handleDelete()" :disabled="isLoading" class="bg-destructive text-destructive-foreground px-4 py-2 rounded-md hover:bg-destructive/90 transition-colors disabled:opacity-50">
                        <span x-show="!isLoading">Cancel Exam</span>
                        <span x-show="isLoading">Canceling...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function examManagement() {
    return {
        searchQuery: '',
        statusFilter: 'all',
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        selectedExamId: null,
        formData: {
            title: '',
            course_id: '',
            grade: '',
            exam_date: '',
            exam_time: '',
            duration: '',
            type: '',
            status: 'Scheduled'
        },
        exams: <?php echo json_encode($exams, 15, 512) ?>,
        openAddDialog() {
            this.resetForm();
            this.showAddModal = true;
        },
        async openEditDialog(examId) {
            try {
                const response = await fetch(`/api/exams/${examId}`);
                const data = await response.json();
                if (data.success) {
                    this.selectedExamId = examId;
                    this.formData = {
                        title: data.data.title,
                        course_id: data.data.course_id || '',
                        grade: data.data.grade,
                        exam_date: data.data.exam_date,
                        exam_time: data.data.exam_time || '',
                        duration: data.data.duration,
                        type: data.data.type,
                        status: data.data.status
                    };
                    this.showEditModal = true;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },
        openDeleteDialog(examId) {
            this.selectedExamId = examId;
            this.showDeleteModal = true;
        },
        isLoading: false,
        errorMessage: '',
        successMessage: '',
        async handleAddSubmit() {
            this.isLoading = true;
            this.errorMessage = '';
            this.successMessage = '';
            try {
                const response = await fetch('/api/exams', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: 'EXM' + String(Date.now()).slice(-6),
                        ...this.formData
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.successMessage = 'Exam scheduled successfully!';
                    this.showAddModal = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to schedule exam');
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
                const response = await fetch(`/api/exams/${this.selectedExamId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.formData)
                });
                const data = await response.json();
                if (data.success) {
                    this.successMessage = 'Exam updated successfully!';
                    this.showEditModal = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to update exam');
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
                const response = await fetch(`/api/exams/${this.selectedExamId}`, {
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
                    this.errorMessage = data.message || 'Failed to delete exam';
                    this.isLoading = false;
                }
            } catch (error) {
                this.errorMessage = 'An error occurred. Please try again.';
                this.isLoading = false;
                console.error('Error:', error);
            }
        },
        fetchExams() {
            const query = this.searchQuery.toLowerCase().trim();
            const status = this.statusFilter;
            if (!query && status === 'all') {
                return;
            }
            // Client-side filtering would be implemented here
            // For now, reload with query params for server-side filtering
            const params = new URLSearchParams();
            if (query) params.set('search', query);
            if (status !== 'all') params.set('status', status);
            if (params.toString()) {
                window.location.href = window.location.pathname + '?' + params.toString();
            }
        },
        resetForm() {
            this.formData = {
                title: '',
                course_id: '',
                grade: '',
                exam_date: '',
                exam_time: '',
                duration: '',
                type: '',
                status: 'Scheduled'
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

<?php echo $__env->make('layouts.dashboard', ['title' => 'Exam', 'userRole' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\school-hub\resources\views/admin/exams/index.blade.php ENDPATH**/ ?>