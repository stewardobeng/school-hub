@extends('layouts.dashboard', ['title' => 'Student Dashboard', 'userRole' => 'student'])

@section('content')
<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-blue-100">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Enrolled Courses</p>
            <p class="text-2xl font-bold text-foreground">6</p>
        </div>
    </div>

    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-green-100">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Completed</p>
            <p class="text-2xl font-bold text-foreground">24</p>
        </div>
    </div>

    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-amber-100">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Pending Tasks</p>
            <p class="text-2xl font-bold text-foreground">5</p>
        </div>
    </div>

    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-purple-100">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">GPA</p>
            <p class="text-2xl font-bold text-foreground">3.8</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Courses Progress -->
    <div class="lg:col-span-2">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50 animate-fade-in">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="text-lg font-semibold">My Courses</h3>
            </div>
            <div class="space-y-4">
                @php
                    $courses = [
                        ['name' => 'Mathematics', 'progress' => 85, 'grade' => 'A', 'nextClass' => 'Mon 9:00 AM'],
                        ['name' => 'Physics', 'progress' => 72, 'grade' => 'B+', 'nextClass' => 'Tue 11:00 AM'],
                        ['name' => 'English Literature', 'progress' => 90, 'grade' => 'A+', 'nextClass' => 'Wed 10:00 AM'],
                        ['name' => 'Chemistry', 'progress' => 68, 'grade' => 'B', 'nextClass' => 'Thu 2:00 PM'],
                    ];
                @endphp
                @foreach($courses as $course)
                    <div class="p-4 bg-muted rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-medium text-foreground">{{ $course['name'] }}</h4>
                            <span class="text-sm font-semibold text-accent">{{ $course['grade'] }}</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="flex-1 bg-background rounded-full h-2">
                                <div class="bg-accent h-2 rounded-full" style="width: {{ $course['progress'] }}%"></div>
                            </div>
                            <span class="text-sm text-muted-foreground">{{ $course['progress'] }}%</span>
                        </div>
                        <p class="text-xs text-muted-foreground mt-2">Next class: {{ $course['nextClass'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Today's Schedule -->
    <div>
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50 animate-fade-in" style="animation-delay: 0.1s">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold">Today's Schedule</h3>
            </div>
            <div class="space-y-4">
                @php
                    $schedule = [
                        ['time' => '9:00 AM', 'subject' => 'Mathematics', 'room' => 'Room 101', 'teacher' => 'Mr. Johnson'],
                        ['time' => '11:00 AM', 'subject' => 'Physics', 'room' => 'Lab 3', 'teacher' => 'Dr. Smith'],
                        ['time' => '2:00 PM', 'subject' => 'English', 'room' => 'Room 205', 'teacher' => 'Ms. Davis'],
                    ];
                @endphp
                @foreach($schedule as $item)
                    <div class="flex gap-4 p-3 bg-muted rounded-lg">
                        <div class="text-center">
                            <p class="text-sm font-semibold text-accent">{{ $item['time'] }}</p>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-foreground">{{ $item['subject'] }}</h4>
                            <p class="text-xs text-muted-foreground">{{ $item['room'] }} • {{ $item['teacher'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Assignments -->
<div class="bg-card rounded-xl p-6 shadow-sm border border-border/50 mt-6 animate-fade-in" style="animation-delay: 0.2s">
    <div class="flex items-center gap-2 mb-4">
        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-lg font-semibold">Upcoming Assignments</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
            $assignments = [
                ['title' => 'Algebra Problem Set', 'course' => 'Mathematics', 'due' => 'Dec 10', 'status' => 'pending'],
                ['title' => 'Lab Report #5', 'course' => 'Physics', 'due' => 'Dec 12', 'status' => 'pending'],
                ['title' => 'Essay: Shakespeare', 'course' => 'English Literature', 'due' => 'Dec 15', 'status' => 'submitted'],
            ];
        @endphp
        @foreach($assignments as $assignment)
            <div class="p-4 rounded-lg border {{ $assignment['status'] === 'submitted' ? 'bg-green-50 border-green-200' : 'bg-card border-border' }}">
                <h4 class="font-medium text-foreground">{{ $assignment['title'] }}</h4>
                <p class="text-sm text-muted-foreground">{{ $assignment['course'] }}</p>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-xs text-muted-foreground">Due: {{ $assignment['due'] }}</span>
                    <span class="text-xs font-medium px-2 py-1 rounded {{ $assignment['status'] === 'submitted' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $assignment['status'] === 'submitted' ? 'Submitted' : 'Pending' }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
