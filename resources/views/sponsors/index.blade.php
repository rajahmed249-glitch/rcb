@extends('layouts.app')

@section('title', 'Our Sponsors')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>{{ __('site.sponsors_page.title') }}</h1>
            <p>{{ __('site.sponsors_page.subtitle') }}</p>
        </div>

        <div class="sponsor-grid">
            @forelse ($sponsors as $sponsor)
                <div class="card sponsor-card">
                    @if ($sponsor->logo)
                        <img class="sponsor-logo" src="{{ asset($sponsor->logo) }}" alt="{{ $sponsor->display_name }}" title="{{ $sponsor->display_name }}" loading="lazy">
                    @endif
                    @if ($sponsor->website)
                        <a href="{{ $sponsor->website }}" target="_blank" rel="noopener">{{ $sponsor->display_name }}</a>
                    @else
                        <span>{{ $sponsor->display_name }}</span>
                    @endif
                </div>
            @empty
                <div class="card"><p class="muted">{{ __('site.no_sponsors') }}</p></div>
            @endforelse
        </div>

        <div class="card" style="margin-top: 2.5rem; text-align:center;">
            <h2 style="margin-bottom:.5rem;">{{ __('site.sponsors_page.become') }}</h2>
            <p class="muted">Want to support the team? Email us at <a href="mailto:info@rcb.com.bd" style="color:var(--accent);">info@rcb.com.bd</a></p>
        </div>
    </div>
@endsection
