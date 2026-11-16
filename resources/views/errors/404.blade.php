@extends('layouts.app')

@section('title', '404 — Page Not Found')

@section('content')
    <div class="container" style="text-align:center; padding: 4rem 0;">
        <h1 style="font-size: 5rem; color: var(--gold);">404</h1>
        <h2 style="margin-bottom: .5rem;">Page Not Found</h2>
        <p class="muted" style="margin-bottom: 1.5rem;">The page you are looking for doesn't exist or has been moved.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Back to Home</a>
    </div>
@endsection
