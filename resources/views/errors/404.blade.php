@extends('layouts.app')

@section('title', '404 — Page Not Found')

@section('content')
    <div class="container" style="text-align:center; padding: 4rem 0;">
        <h1 style="font-size: 5rem; color: var(--gold);">404</h1>
        <h2 style="margin-bottom: .5rem;">{{ __('site.page_not_found') }}</h2>
        <p class="muted" style="margin-bottom: 1.5rem;">{{ __('site.page_not_found_text') }}</p>
        <a href="{{ route('home') }}" class="btn btn-primary">{{ __('site.back_to_home') }}</a>
    </div>
@endsection
