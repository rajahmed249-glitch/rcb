@extends('layouts.app')

@section('title', 'Our Sponsors')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>Our Sponsors</h1>
            <p>The partners who support REAL CHALLENGER BOYS on and off the field.</p>
        </div>

        <div class="sponsor-grid">
            @forelse ($sponsors as $sponsor)
                <div class="card sponsor-card">
                    @if ($sponsor->website)
                        <a href="{{ $sponsor->website }}" target="_blank" rel="noopener">{{ $sponsor->name }}</a>
                    @else
                        {{ $sponsor->name }}
                    @endif
                </div>
            @empty
                <div class="card"><p class="muted">No sponsors listed yet.</p></div>
            @endforelse
        </div>

        <div class="card" style="margin-top: 2.5rem; text-align:center;">
            <h2 style="margin-bottom:.5rem;">Become a Sponsor 🤝</h2>
            <p class="muted">Want to support the team? Email us at <a href="mailto:info@rcb.com.bd" style="color:var(--accent);">info@rcb.com.bd</a></p>
        </div>
    </div>
@endsection
