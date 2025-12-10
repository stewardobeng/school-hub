@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-foreground mb-4">404</h1>
        <p class="text-xl text-muted-foreground mb-6">Page Not Found</p>
        <a href="/" class="bg-primary text-primary-foreground px-6 py-3 rounded-md hover:bg-primary/90">
            Go Home
        </a>
    </div>
</div>
@endsection

