@php
    $roles = [
        [
            'title' => 'Admin Dashboard',
            'description' => 'Manage students, teachers, courses, and view analytics',
            'icon' => 'Shield',
            'route' => 'admin.dashboard',
            'color' => 'bg-blue-100 text-blue-600',
        ],
        [
            'title' => 'Student Dashboard',
            'description' => 'View courses, grades, assignments, and schedule',
            'icon' => 'GraduationCap',
            'route' => 'student.dashboard',
            'color' => 'bg-green-100 text-green-600',
        ],
        [
            'title' => 'Teacher Dashboard',
            'description' => 'Manage classes, attendance, and grading',
            'icon' => 'Users',
            'route' => 'teacher.dashboard',
            'color' => 'bg-purple-100 text-purple-600',
        ],
    ];
    
    $features = ['Student Management', 'Teacher Management', 'Course Management', 'Attendance Tracking', 'Grade Management', 'Payment System'];
@endphp

@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-background flex items-center justify-center p-6">
        <div class="max-w-4xl w-full">
            <!-- Header -->
            <div class="text-center mb-12 animate-fade-in">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <svg viewBox="0 0 24 24" class="w-12 h-12" fill="none">
                            <circle cx="12" cy="8" r="3" fill="hsl(var(--accent))" />
                            <circle cx="6" cy="16" r="2.5" fill="hsl(var(--accent))" />
                            <circle cx="18" cy="16" r="2.5" fill="hsl(var(--accent))" />
                            <path d="M12 11v3M8 14l2 1M16 14l-2 1" stroke="hsl(var(--accent))" stroke-width="1.5" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold text-foreground">SCHOOL</h1>
                </div>
                <p class="text-lg text-muted-foreground max-w-md mx-auto">
                    Complete School Management System
                </p>
            </div>

            <!-- Role Selection -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($roles as $index => $role)
                    <div class="bg-card rounded-xl p-6 shadow-sm border border-border/50 hover:shadow-lg transition-all duration-300 animate-fade-in cursor-pointer group" style="animation-delay: {{ $index * 0.1 }}s">
                        <div class="text-center pb-2">
                            <div class="w-16 h-16 rounded-full {{ $role['color'] }} flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                @if($role['icon'] === 'Shield')
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                @elseif($role['icon'] === 'GraduationCap')
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v7"></path></svg>
                                @elseif($role['icon'] === 'Users')
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                @endif
                            </div>
                            <h3 class="text-xl font-semibold text-foreground mb-2">{{ $role['title'] }}</h3>
                            <p class="text-sm text-muted-foreground mb-4">{{ $role['description'] }}</p>
                        </div>
                        <div class="pt-2">
                            <a href="{{ route($role['route']) }}">
                                <button class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-medium py-2 px-4 rounded-md transition-colors">
                                    Enter Dashboard
                                </button>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Features -->
            <div class="mt-12 text-center animate-fade-in" style="animation-delay: 0.4s">
                <p class="text-sm text-muted-foreground mb-4">Key Features</p>
                <div class="flex flex-wrap justify-center gap-4">
                    @foreach($features as $feature)
                        <span class="px-4 py-2 bg-muted rounded-full text-sm text-muted-foreground">
                            {{ $feature }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

