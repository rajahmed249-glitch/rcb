@extends('layouts.app')

@section('title', 'About')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>{{ __('site.about.title') }}</h1>
            <p>{{ __('site.about.subtitle') }}</p>
        </div>

        <section class="section">
            <div class="card about-card">
                <h2>{{ __('site.about.who_we_are') }}</h2>
                <p>{{ $whoText }}</p>
                <p>{{ $believeText }}</p>
            </div>
        </section>

        <section class="section">
            <div class="stats-grid">
                <div class="card stat-card">
                    <span class="stat-number">{{ $matchesPlayed }}</span>
                    <span class="stat-label">{{ __('site.records.matches_played') }}</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $wins }}</span>
                    <span class="stat-label">{{ __('site.about.victories') }}</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $playerCount }}</span>
                    <span class="stat-label">{{ __('site.about.squad_players') }}</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $since }}</span>
                    <span class="stat-label">{{ __('site.about.since') }}</span>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="about-columns">
                <div class="card">
                    <h2>{{ __('site.about.mission') }}</h2>
                    <p>{{ $missionText }}</p>
                </div>
                <div class="card">
                    <h2>{{ __('site.about.vision') }}</h2>
                    <p>{{ $visionText }}</p>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="card about-card">
                <h2>{{ __('site.about.home_ground') }}</h2>
                <p>{{ $homeText }}</p>
                <p>{{ __('site.about.join_before') }} <a href="{{ route('be-a-player.create') }}" class="text-link">{{ __('site.be_a_player') }}</a> {{ __('site.about.join_after') }}</p>
            </div>
        </section>
    </div>
@endsection
