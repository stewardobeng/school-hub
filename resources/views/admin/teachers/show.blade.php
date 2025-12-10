@extends('layouts.dashboard', ['title' => 'Teacher Details', 'userRole' => 'admin'])

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'overview' }">
    <!-- Back Button -->
    <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center gap-2 text-foreground hover:text-primary transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Teachers
    </a>

    <!-- Teacher Profile Header -->
    <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
        <div class="flex items-start gap-6">
            <div class="w-32 h-32 border-4 border-primary rounded-full bg-primary text-primary-foreground flex items-center justify-center text-4xl font-semibold">
                {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $teacher->first_name }} {{ $teacher->last_name }}</h1>
                        <div class="flex items-center gap-4 text-muted-foreground">
                            <span class="font-mono text-sm">ID: {{ $teacher->id }}</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $teacher->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $teacher->status }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('admin.teachers.index') }}?edit={{ $teacher->id }}" class="bg-primary text-primary-foreground px-4 py-2 rounded-md hover:bg-primary/90 transition-colors">Edit Teacher</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span class="text-sm">{{ $teacher->subject ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm">{{ $teacher->email }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-sm">{{ $teacher->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm">Joined: {{ \Carbon\Carbon::parse($teacher->join_date)->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-card rounded-xl p-4 shadow-sm border border-border/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Active Courses</p>
                    <p class="text-2xl font-bold">{{ $courses->count() }}</p>
                </div>
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
        <div class="bg-card rounded-xl p-4 shadow-sm border border-border/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Total Students</p>
                    <p class="text-2xl font-bold">{{ $students->count() }}</p>
                </div>
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
        <div class="bg-card rounded-xl p-4 shadow-sm border border-border/50">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-muted-foreground">Experience</p>
                    <p class="text-2xl font-bold">{{ $teacher->experience ?? 'N/A' }}</p>
                </div>
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
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
            <button @click="activeTab = 'students'" 
                    :class="activeTab === 'students' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">Students</button>
            <button @click="activeTab = 'schedule'" 
                    :class="activeTab === 'schedule' ? 'border-b-2 border-primary text-foreground' : 'text-muted-foreground'"
                    class="pb-2 px-1 font-medium transition-colors">Schedule</button>
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
                            <p class="text-sm text-muted-foreground">Join Date</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($teacher->join_date)->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-muted-foreground">Subject</p>
                            <p class="font-medium">{{ $teacher->subject ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @if($teacher->education)
                    <div>
                        <p class="text-sm text-muted-foreground">Education</p>
                        <p class="font-medium">{{ $teacher->education }}</p>
                    </div>
                    @endif
                    @if($teacher->address)
                    <div>
                        <p class="text-sm text-muted-foreground">Address</p>
                        <p class="font-medium">{{ $teacher->address }}</p>
                    </div>
                    @endif
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
                Teaching Courses
            </h3>
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Course Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Course Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Students</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($courses as $course)
                            <tr>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded border font-mono">{{ $course->code ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 font-medium">{{ $course->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $course->grade ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $course->student_count ?? 0 }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-muted-foreground">No courses assigned</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Students Tab -->
    <div x-show="activeTab === 'students'" class="space-y-6">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Students
            </h3>
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Email</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($students as $student)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td class="px-6 py-4 font-mono text-sm">{{ $student->id }}</td>
                                <td class="px-6 py-4">{{ $student->grade ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-muted-foreground">{{ $student->email ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-muted-foreground">No students</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Schedule Tab -->
    <div x-show="activeTab === 'schedule'" class="space-y-6">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50">
            <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Teaching Schedule
            </h3>
            <div class="rounded-md border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-muted/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">Schedule</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($schedule as $item)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $item->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $item->grade ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $item->schedule ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-muted-foreground">No schedule available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>
[x-cloak] { display: none !important; }
</style>
@endpush
@endsection
