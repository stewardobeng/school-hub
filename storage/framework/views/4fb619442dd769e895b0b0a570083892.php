

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="attendanceManagement()">
    <?php
        $overallAttendance = $attendance->count() > 0 ? ($attendance->sum('present') / $attendance->sum('total_students')) * 100 : 0;
        $todayPresent = $attendance->filter(function($r) { return \Carbon\Carbon::parse($r->attendance_date)->isToday(); })->sum('present');
        $todayAbsent = $attendance->filter(function($r) { return \Carbon\Carbon::parse($r->attendance_date)->isToday(); })->sum('absent');
    ?>
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Overall Attendance</p>
                <p class="text-2xl font-bold text-foreground"><?php echo e(number_format($overallAttendance, 1)); ?>%</p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-green-100">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Present Today</p>
                <p class="text-2xl font-bold text-foreground"><?php echo e($todayPresent); ?></p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-red-100">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Absent Today</p>
                <p class="text-2xl font-bold text-foreground"><?php echo e($todayAbsent); ?></p>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Calendar -->
        <div class="lg:col-span-1">
            <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Calendar
                </h3>
                <input type="date" x-model="selectedDate" @change="filterByDate()" 
                       class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
            </div>
        </div>

        <!-- Attendance Records -->
        <div class="lg:col-span-3">
            <div class="bg-card rounded-xl shadow-sm border border-border/50">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold">Attendance Records</h2>
                            <p class="text-sm text-muted-foreground mt-1">View and manage daily attendance</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <select x-model="classFilter" @change="fetchAttendance()" class="w-40 px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="all">All Classes</option>
                                <option value="grade9">Grade 9</option>
                                <option value="grade10">Grade 10</option>
                                <option value="grade11">Grade 11</option>
                                <option value="grade12">Grade 12</option>
                            </select>
                            <button @click="openAddDialog()" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Mark Attendance
                                </span>
                            </button>
                        </div>
                    </div>
                    
                    <div class="rounded-md border overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-muted/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Teacher</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Present</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Absent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Late</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-muted-foreground uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border">
                                <?php $__empty_1 = true; $__currentLoopData = $attendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        $attendanceRate = $record->total_students > 0 ? (($record->present / $record->total_students) * 100) : 0;
                                    ?>
                                    <tr class="hover:bg-muted/50">
                                        <td class="px-6 py-4 font-medium"><?php echo e($record->attendance_date ? \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') : 'N/A'); ?></td>
                                        <td class="px-6 py-4"><?php echo e($record->class_name ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-muted-foreground"><?php echo e(($record->teacher_first_name ?? '') . ' ' . ($record->teacher_last_name ?? '')); ?></td>
                                        <td class="px-6 py-4"><?php echo e($record->total_students ?? 0); ?></td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="font-medium text-green-600"><?php echo e($record->present ?? 0); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span class="font-medium text-red-600"><?php echo e($record->absent ?? 0); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="font-medium text-amber-600"><?php echo e($record->late ?? 0); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-1 text-xs rounded-full <?php echo e($record->status === 'Completed' ? 'bg-green-100 text-green-800' : 'bg-muted'); ?>">
                                                    <?php echo e($record->status ?? 'Pending'); ?>

                                                </span>
                                                <span class="text-xs text-muted-foreground">(<?php echo e(number_format($attendanceRate, 1)); ?>%)</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            <div class="flex items-center justify-end gap-2">
                                                <button @click="openEditDialog('<?php echo e($record->id); ?>')" class="text-primary hover:text-primary/80">Edit</button>
                                                <button @click="openDeleteDialog('<?php echo e($record->id); ?>')" class="text-destructive hover:text-destructive/80">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-muted-foreground">No attendance records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Attendance Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showAddModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Mark Attendance</h3>
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
                            <label class="text-sm font-medium">Class *</label>
                            <input type="text" x-model="formData.class_name" required placeholder="Grade 10 - Mathematics" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
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
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Date *</label>
                        <input type="date" x-model="formData.attendance_date" required 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Total *</label>
                            <input type="number" x-model="formData.total_students" required placeholder="30" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Present *</label>
                            <input type="number" x-model="formData.present" required placeholder="28" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Absent *</label>
                            <input type="number" x-model="formData.absent" required placeholder="2" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Late</label>
                            <input type="number" x-model="formData.late" placeholder="0" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end pt-4">
                        <button type="button" @click="showAddModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span x-show="!isLoading">Mark Attendance</span>
                            <span x-show="isLoading">Marking...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Attendance Modal -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showEditModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Edit Attendance Record</h3>
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
                            <label class="text-sm font-medium">Class *</label>
                            <input type="text" x-model="formData.class_name" required placeholder="Grade 10 - Mathematics" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
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
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Date *</label>
                        <input type="date" x-model="formData.attendance_date" required 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <div class="grid grid-cols-4 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Total *</label>
                            <input type="number" x-model="formData.total_students" required placeholder="30" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Present *</label>
                            <input type="number" x-model="formData.present" required placeholder="28" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Absent *</label>
                            <input type="number" x-model="formData.absent" required placeholder="2" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Late</label>
                            <input type="number" x-model="formData.late" placeholder="0" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end pt-4">
                        <button type="button" @click="showEditModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span x-show="!isLoading">Update Record</span>
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
                <h3 class="text-lg font-semibold mb-2">Delete Attendance Record?</h3>
                <p class="text-sm text-muted-foreground mb-6">This will permanently delete the attendance record. This action cannot be undone.</p>
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
function attendanceManagement() {
    return {
        selectedDate: new Date().toISOString().split('T')[0],
        classFilter: 'all',
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        selectedRecordId: null,
        formData: {
            class_name: '',
            teacher_id: '',
            attendance_date: new Date().toISOString().split('T')[0],
            total_students: '',
            present: '',
            absent: '',
            late: '0'
        },
        attendance: <?php echo json_encode($attendance, 15, 512) ?>,
        openAddDialog() {
            this.resetForm();
            this.showAddModal = true;
        },
        async openEditDialog(recordId) {
            try {
                const response = await fetch(`/api/attendance/${recordId}`);
                const data = await response.json();
                if (data.success) {
                    this.selectedRecordId = recordId;
                    this.formData = {
                        class_name: data.data.class_name,
                        teacher_id: data.data.teacher_id || '',
                        attendance_date: data.data.attendance_date,
                        total_students: data.data.total_students.toString(),
                        present: data.data.present.toString(),
                        absent: data.data.absent.toString(),
                        late: (data.data.late || 0).toString()
                    };
                    this.showEditModal = true;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },
        openDeleteDialog(recordId) {
            this.selectedRecordId = recordId;
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
                const total = parseInt(this.formData.total_students);
                const present = parseInt(this.formData.present);
                const absent = parseInt(this.formData.absent);
                const late = parseInt(this.formData.late || '0');
                
                if (present + absent + late !== total) {
                    this.errorMessage = 'Present + Absent + Late must equal Total Students';
                    this.isLoading = false;
                    return;
                }
                
                const response = await fetch('/api/attendance', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: 'ATT' + String(Date.now()).slice(-6),
                        status: 'Completed',
                        ...this.formData,
                        total_students: total,
                        present: present,
                        absent: absent,
                        late: late
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.successMessage = 'Attendance marked successfully!';
                    this.showAddModal = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to mark attendance');
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
                const total = parseInt(this.formData.total_students);
                const present = parseInt(this.formData.present);
                const absent = parseInt(this.formData.absent);
                const late = parseInt(this.formData.late || '0');
                
                if (present + absent + late !== total) {
                    this.errorMessage = 'Present + Absent + Late must equal Total Students';
                    this.isLoading = false;
                    return;
                }
                
                const response = await fetch(`/api/attendance/${this.selectedRecordId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        ...this.formData,
                        total_students: total,
                        present: present,
                        absent: absent,
                        late: late
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.successMessage = 'Attendance updated successfully!';
                    this.showEditModal = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to update attendance');
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
                const response = await fetch(`/api/attendance/${this.selectedRecordId}`, {
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
                    this.errorMessage = data.message || 'Failed to delete attendance record';
                    this.isLoading = false;
                }
            } catch (error) {
                this.errorMessage = 'An error occurred. Please try again.';
                this.isLoading = false;
                console.error('Error:', error);
            }
        },
        filterByDate() {
            if (this.selectedDate) {
                window.location.href = window.location.pathname + '?start_date=' + this.selectedDate + '&end_date=' + this.selectedDate;
            }
        },
        fetchAttendance() {
            if (this.classFilter !== 'all') {
                window.location.href = window.location.pathname + '?class_name=' + this.classFilter;
            } else {
                window.location.href = window.location.pathname;
            }
        },
        resetForm() {
            this.formData = {
                class_name: '',
                teacher_id: '',
                attendance_date: new Date().toISOString().split('T')[0],
                total_students: '',
                present: '',
                absent: '',
                late: '0'
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

<?php echo $__env->make('layouts.dashboard', ['title' => 'Attendance', 'userRole' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\school-hub\resources\views/admin/attendance/index.blade.php ENDPATH**/ ?>