@extends('layouts.dashboard', ['title' => 'Teacher Dashboard', 'userRole' => 'teacher'])

@section('content')
<div x-data="{
    startClass(className) {
        alert('Starting class: ' + className);
        // In a real application, this would navigate to the class session page or open a modal
        // window.location.href = '/teacher/class/' + className;
    }
}">
<!-- Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-blue-100">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">My Classes</p>
            <p class="text-2xl font-bold text-foreground">5</p>
        </div>
    </div>

    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-purple-100">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Total Students</p>
            <p class="text-2xl font-bold text-foreground">156</p>
        </div>
    </div>

    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-green-100">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Attendance Rate</p>
            <p class="text-2xl font-bold text-foreground">94%</p>
        </div>
    </div>

    <div class="stat-card flex items-center gap-4 animate-fade-in">
        <div class="w-12 h-12 rounded-full flex items-center justify-center bg-amber-100">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <div>
            <p class="text-sm text-muted-foreground">Classes Today</p>
            <p class="text-2xl font-bold text-foreground">3</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- My Classes -->
    <div class="lg:col-span-2">
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50 animate-fade-in">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="text-lg font-semibold">My Classes</h3>
                </div>
                <a href="{{ route('teacher.classes') }}" class="bg-muted text-foreground px-3 py-1 rounded-md text-sm hover:bg-muted/80 transition-colors">View All</a>
            </div>
            <div class="space-y-4">
                @php
                    $classes = [
                        ['name' => 'Mathematics 101', 'grade' => '6th Grade', 'students' => 32, 'time' => 'Mon, Wed, Fri 9:00 AM'],
                        ['name' => 'Advanced Algebra', 'grade' => '8th Grade', 'students' => 28, 'time' => 'Tue, Thu 11:00 AM'],
                        ['name' => 'Geometry Basics', 'grade' => '7th Grade', 'students' => 30, 'time' => 'Mon, Wed 2:00 PM'],
                    ];
                @endphp
                @foreach($classes as $class)
                    <div class="flex items-center justify-between p-4 bg-muted rounded-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg bg-accent/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-medium text-foreground">{{ $class['name'] }}</h4>
                                <p class="text-sm text-muted-foreground">{{ $class['grade'] }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-foreground">{{ $class['students'] }} students</p>
                            <p class="text-xs text-muted-foreground">{{ $class['time'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Today's Attendance -->
    <div>
        <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50 animate-fade-in" style="animation-delay: 0.1s">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h3 class="text-lg font-semibold">Today's Attendance</h3>
            </div>
            <div class="flex items-center justify-center mb-4">
                <div class="relative w-40 h-40">
                    <canvas id="attendanceChart"></canvas>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-foreground">32</p>
                            <p class="text-xs text-muted-foreground">Total</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                    <span class="text-sm text-muted-foreground">Present: 28</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <span class="text-sm text-muted-foreground">Absent: 2</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                    <span class="text-sm text-muted-foreground">Late: 2</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Recent Submissions -->
    <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50 animate-fade-in" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h3 class="text-lg font-semibold">Recent Submissions</h3>
            </div>
            <a href="{{ route('teacher.grading') }}" class="bg-muted text-foreground px-3 py-1 rounded-md text-sm hover:bg-muted/80 transition-colors">View All</a>
        </div>
        <div class="space-y-4">
            @php
                $submissions = [
                    ['student' => 'Emma Wilson', 'assignment' => 'Homework #5', 'time' => '2 hours ago', 'status' => 'pending'],
                    ['student' => 'James Brown', 'assignment' => 'Quiz #3', 'time' => '5 hours ago', 'status' => 'graded'],
                    ['student' => 'Sophia Lee', 'assignment' => 'Project Report', 'time' => '1 day ago', 'status' => 'pending'],
                ];
            @endphp
            @foreach($submissions as $submission)
                <div class="flex items-center justify-between p-3 bg-muted rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center text-sm font-semibold">
                            {{ substr($submission['student'], 0, 1) }}{{ substr(explode(' ', $submission['student'])[1] ?? '', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-foreground">{{ $submission['student'] }}</p>
                            <p class="text-sm text-muted-foreground">{{ $submission['assignment'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 text-xs font-medium px-2 py-1 rounded {{ $submission['status'] === 'graded' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            @if($submission['status'] === 'graded')
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            @endif
                            {{ $submission['status'] === 'graded' ? 'Graded' : 'Pending' }}
                        </span>
                        <p class="text-xs text-muted-foreground mt-1">{{ $submission['time'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Today's Schedule -->
    <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50 animate-fade-in" style="animation-delay: 0.3s">
        <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-semibold">Today's Schedule</h3>
        </div>
        <div class="space-y-4">
            @php
                $upcomingClasses = [
                    ['time' => '9:00 AM', 'class' => 'Mathematics 101', 'room' => 'Room 101', 'students' => 32],
                    ['time' => '11:00 AM', 'class' => 'Advanced Algebra', 'room' => 'Room 203', 'students' => 28],
                    ['time' => '2:00 PM', 'class' => 'Geometry Basics', 'room' => 'Room 105', 'students' => 30],
                ];
            @endphp
            @foreach($upcomingClasses as $item)
                <div class="flex items-center gap-4 p-3 bg-muted rounded-lg">
                    <div class="text-center min-w-[60px]">
                        <p class="text-sm font-semibold text-accent">{{ $item['time'] }}</p>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-medium text-foreground">{{ $item['class'] }}</h4>
                        <p class="text-xs text-muted-foreground">{{ $item['room'] }} • {{ $item['students'] }} students</p>
                    </div>
                    <button @click="startClass('{{ $item['class'] }}')" class="bg-muted text-foreground px-3 py-1 rounded-md text-sm hover:bg-muted/80 transition-colors">Start</button>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('attendanceChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Absent', 'Late'],
                    datasets: [{
                        data: [28, 2, 2],
                        backgroundColor: ['#22c55e', '#ef4444', '#f59e0b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
</div>
@endsection
