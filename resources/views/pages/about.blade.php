@extends('layouts.app')

@section('title', 'About')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>About RCB</h1>
            <p>The story of REAL CHALLENGER BOYS — play with passion, win with honor.</p>
        </div>

        <section class="section">
            <div class="card about-card">
                <h2>🏏 Who We Are</h2>
                <p>REAL CHALLENGER BOYS (RCB) is a cricket team from Dhaka, Bangladesh. What started as a group of
                neighbourhood friends with a shared love for cricket has grown into a competitive squad that plays
                regular fixtures across Dhaka and beyond.</p>
                <p>We believe in discipline, teamwork and sportsmanship. Every match is an opportunity to improve,
                every player has a role, and every victory belongs to the whole team.</p>
            </div>
        </section>

        <section class="section">
            <div class="stats-grid">
                <div class="card stat-card">
                    <span class="stat-number">{{ $matchesPlayed }}</span>
                    <span class="stat-label">Matches Played</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $wins }}</span>
                    <span class="stat-label">Victories</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">{{ $playerCount }}</span>
                    <span class="stat-label">Squad Players</span>
                </div>
                <div class="card stat-card">
                    <span class="stat-number">2004</span>
                    <span class="stat-label">RCB Since</span>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="about-columns">
                <div class="card">
                    <h2>🎯 Our Mission</h2>
                    <p>To build a competitive yet friendly cricket team that represents our community with pride,
                    develops young talent, and plays every game in the true spirit of cricket.</p>
                </div>
                <div class="card">
                    <h2>👁️ Our Vision</h2>
                    <p>To become one of the most respected cricket teams in Dhaka — known for our fighting spirit,
                    our unity on and off the field, and the passion we bring to every innings.</p>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="card about-card">
                <h2>📍 Home Ground</h2>
                <p>We practice and play at grounds around Mirpur and Shewrapara, Dhaka. Our home address is
                East Shewrapara, Begum Rokeya Sarani, Mirpur, Dhaka - 1216, Bangladesh.</p>
                <p>Want to join the squad? Head over to the <a href="{{ route('be-a-player.create') }}" class="text-link">Be a Player</a> page and register your interest.</p>
            </div>
        </section>
    </div>
@endsection
