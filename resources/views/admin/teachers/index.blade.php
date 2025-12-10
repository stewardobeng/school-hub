@extends('layouts.dashboard', ['title' => 'Teachers', 'userRole' => 'admin'])

@section('content')
<div class="space-y-6" x-data="teacherManagement()">
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-purple-100">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Total Teachers</p>
                <p class="text-2xl font-bold text-foreground">{{ $teachers->count() }}</p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-blue-100">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">Active Teachers</p>
                <p class="text-2xl font-bold text-foreground">{{ $teachers->where('status', 'Active')->count() }}</p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 rounded-full flex items-center justify-center bg-green-100">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-muted-foreground">New Hires This Year</p>
                <p class="text-2xl font-bold text-foreground">{{ $teachers->filter(function($t) { return \Carbon\Carbon::parse($t->join_date)->isCurrentYear(); })->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-card rounded-xl shadow-sm border border-border/50">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold">Teacher Management</h2>
                    <p class="text-sm text-muted-foreground mt-1">Manage faculty members and their assignments</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchTeachers()" placeholder="Search teachers..." 
                               class="pl-10 pr-4 py-2 w-64 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <button @click="openAddDialog()" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Add Teacher
                        </span>
                    </button>
                </div>
            </div>
            
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Teacher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-muted-foreground uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border" x-show="searchQuery === ''">
                        @forelse($teachers as $teacher)
                            <tr class="hover:bg-muted/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-semibold">
                                            {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-foreground">{{ $teacher->first_name }} {{ $teacher->last_name }}</div>
                                            <div class="text-sm text-muted-foreground">{{ $teacher->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-sm">{{ $teacher->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <span>{{ $teacher->subject ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 text-sm">
                                            <svg class="w-3 h-3 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-muted-foreground">{{ $teacher->email }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm">
                                            <svg class="w-3 h-3 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span class="text-muted-foreground">{{ $teacher->phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $teacher->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $teacher->status ?? 'Active' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.teachers.show', $teacher->id) }}" class="text-primary hover:text-primary/80">View</a>
                                        <button @click="openEditDialog('{{ $teacher->id }}')" class="text-primary hover:text-primary/80">Edit</button>
                                        <button @click="openDeleteDialog('{{ $teacher->id }}')" class="text-destructive hover:text-destructive/80">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-muted-foreground">No teachers found</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tbody class="divide-y divide-border" x-show="searchQuery !== ''" x-cloak>
                        <template x-for="teacher in filteredTeachers" :key="teacher.id">
                            <tr class="hover:bg-muted/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-semibold" x-text="(teacher.first_name?.[0] || '') + (teacher.last_name?.[0] || '')"></div>
                                        <div>
                                            <div class="font-medium text-foreground" x-text="(teacher.first_name || '') + ' ' + (teacher.last_name || '')"></div>
                                            <div class="text-sm text-muted-foreground" x-text="teacher.email || ''"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-mono text-sm" x-text="teacher.id"></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                        <span x-text="teacher.subject || 'N/A'"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 text-sm">
                                            <svg class="w-3 h-3 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-muted-foreground" x-text="teacher.email || 'N/A'"></span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm">
                                            <svg class="w-3 h-3 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span class="text-muted-foreground" x-text="teacher.phone || 'N/A'"></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full" :class="teacher.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" x-text="teacher.status || 'Active'"></span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <a :href="`{{ route('admin.teachers.index') }}/${teacher.id}`" class="text-primary hover:text-primary/80">View</a>
                                        <button @click="openEditDialog(teacher.id)" class="text-primary hover:text-primary/80">Edit</button>
                                        <button @click="openDeleteDialog(teacher.id)" class="text-destructive hover:text-destructive/80">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="filteredTeachers.length === 0">
                            <td colspan="6" class="px-6 py-4 text-center text-muted-foreground">No teachers found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Teacher Modal -->
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showAddModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Add New Teacher</h3>
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
                            <label class="text-sm font-medium">First Name *</label>
                            <input type="text" x-model="formData.firstName" required placeholder="Robert" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Last Name *</label>
                            <input type="text" x-model="formData.lastName" required placeholder="Anderson" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Email *</label>
                        <input type="email" x-model="formData.email" required placeholder="robert.a@school.edu" 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Phone *</label>
                        <input type="tel" x-model="formData.phone" required placeholder="+1 234-567-9000" 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Subject *</label>
                            <select x-model="formData.subject" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select subject</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="Science">Science</option>
                                <option value="English">English</option>
                                <option value="History">History</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Physical Education">Physical Education</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Join Date *</label>
                            <input type="date" x-model="formData.joinDate" required 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end pt-4">
                        <button type="button" @click="showAddModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span x-show="!isLoading">Add Teacher</span>
                            <span x-show="isLoading">Adding...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Teacher Modal -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click.self="showEditModal = false">
        <div class="bg-card rounded-xl shadow-lg border border-border/50 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Edit Teacher</h3>
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
                            <label class="text-sm font-medium">First Name *</label>
                            <input type="text" x-model="formData.firstName" required placeholder="Robert" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Last Name *</label>
                            <input type="text" x-model="formData.lastName" required placeholder="Anderson" 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Email *</label>
                        <input type="email" x-model="formData.email" required placeholder="robert.a@school.edu" 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Phone *</label>
                        <input type="tel" x-model="formData.phone" required placeholder="+1 234-567-9000" 
                               class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Subject *</label>
                            <select x-model="formData.subject" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="">Select subject</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="Science">Science</option>
                                <option value="English">English</option>
                                <option value="History">History</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Physical Education">Physical Education</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Join Date *</label>
                            <input type="date" x-model="formData.joinDate" required 
                                   class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Status *</label>
                            <select x-model="formData.status" required class="w-full px-3 py-2 bg-background border border-border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-ring">
                                <option value="Active">Active</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 justify-end pt-4">
                        <button type="button" @click="showEditModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                        <button type="submit" :disabled="isLoading" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors disabled:opacity-50">
                            <span x-show="!isLoading">Update Teacher</span>
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
                <h3 class="text-lg font-semibold mb-2">Are you sure?</h3>
                <p class="text-sm text-muted-foreground mb-6">This action cannot be undone. This will permanently remove the teacher from the faculty and remove all associated data.</p>
                <div x-show="errorMessage" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-md text-sm" x-text="errorMessage"></div>
                <div class="flex gap-3 justify-end">
                    <button @click="showDeleteModal = false" :disabled="isLoading" class="bg-muted text-foreground px-4 py-2 rounded-md hover:bg-muted/80 transition-colors disabled:opacity-50">Cancel</button>
                    <button @click="handleDelete()" :disabled="isLoading" class="bg-destructive text-destructive-foreground px-4 py-2 rounded-md hover:bg-destructive/90 transition-colors disabled:opacity-50">
                        <span x-show="!isLoading">Remove</span>
                        <span x-show="isLoading">Removing...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function teacherManagement() {
    return {
        searchQuery: '',
        showAddModal: false,
        showEditModal: false,
        showDeleteModal: false,
        selectedTeacherId: null,
        formData: {
            firstName: '',
            lastName: '',
            email: '',
            phone: '',
            subject: '',
            joinDate: new Date().toISOString().split('T')[0],
            status: 'Active'
        },
        teachers: @json($teachers),
        filteredTeachers: @json($teachers),
        isLoading: false,
        errorMessage: '',
        successMessage: '',
        openAddDialog() {
            this.resetForm();
            this.showAddModal = true;
            this.errorMessage = '';
            this.successMessage = '';
        },
        async openEditDialog(teacherId) {
            this.isLoading = true;
            this.errorMessage = '';
            this.successMessage = '';
            try {
                const response = await fetch(`/api/teachers/${teacherId}`);
                const data = await response.json();
                if (data.success) {
                    this.selectedTeacherId = teacherId;
                    this.formData = {
                        firstName: data.data.first_name,
                        lastName: data.data.last_name,
                        email: data.data.email,
                        phone: data.data.phone || '',
                        subject: data.data.subject,
                        joinDate: data.data.join_date,
                        status: data.data.status
                    };
                    this.showEditModal = true;
                } else {
                    this.errorMessage = 'Failed to load teacher data';
                }
            } catch (error) {
                this.errorMessage = 'Error loading teacher data';
                console.error('Error:', error);
            } finally {
                this.isLoading = false;
            }
        },
        openDeleteDialog(teacherId) {
            this.selectedTeacherId = teacherId;
            this.showDeleteModal = true;
        },
        async handleAddSubmit() {
            this.isLoading = true;
            this.errorMessage = '';
            this.successMessage = '';
            try {
                const response = await fetch('/api/teachers', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        id: 'TC' + String(Date.now()).slice(-6),
                        first_name: this.formData.firstName,
                        last_name: this.formData.lastName,
                        email: this.formData.email,
                        phone: this.formData.phone,
                        subject: this.formData.subject,
                        join_date: this.formData.joinDate,
                        status: this.formData.status
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.successMessage = 'Teacher added successfully!';
                    this.showAddModal = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to add teacher');
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
                const response = await fetch(`/api/teachers/${this.selectedTeacherId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        first_name: this.formData.firstName,
                        last_name: this.formData.lastName,
                        email: this.formData.email,
                        phone: this.formData.phone,
                        subject: this.formData.subject,
                        join_date: this.formData.joinDate,
                        status: this.formData.status
                    })
                });
                const data = await response.json();
                if (data.success) {
                    this.successMessage = 'Teacher updated successfully!';
                    this.showEditModal = false;
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Failed to update teacher');
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
                const response = await fetch(`/api/teachers/${this.selectedTeacherId}`, {
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
                    this.errorMessage = data.message || 'Failed to delete teacher';
                    this.isLoading = false;
                }
            } catch (error) {
                this.errorMessage = 'An error occurred. Please try again.';
                this.isLoading = false;
                console.error('Error:', error);
            }
        },
        fetchTeachers() {
            const query = this.searchQuery.toLowerCase().trim();
            if (!query) {
                this.filteredTeachers = this.teachers;
                return;
            }
            this.filteredTeachers = this.teachers.filter(teacher => {
                const fullName = `${teacher.first_name} ${teacher.last_name}`.toLowerCase();
                const email = (teacher.email || '').toLowerCase();
                const id = (teacher.id || '').toLowerCase();
                const subject = (teacher.subject || '').toLowerCase();
                return fullName.includes(query) || email.includes(query) || id.includes(query) || subject.includes(query);
            });
        },
        resetForm() {
            this.formData = {
                firstName: '',
                lastName: '',
                email: '',
                phone: '',
                subject: '',
                joinDate: new Date().toISOString().split('T')[0],
                status: 'Active'
            };
        }
    }
}
</script>
<style>
[x-cloak] { display: none !important; }
</style>
@endpush
@endsection
