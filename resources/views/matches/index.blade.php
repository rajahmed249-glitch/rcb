@extends('layouts.app')

@section('title', 'Matches')

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>Matches</h1>
            <p>All fixtures and results of REAL CHALLENGER BOYS.</p>
        </div>

        <div class="filter-tabs">
            <a href="{{ route('matches.index') }}" class="{{ $status === 'all' ? 'active' : '' }}">All</a>
            <a href="{{ route('matches.index', ['status' => 'completed']) }}" class="{{ $status === 'completed' ? 'active' : '' }}">Completed</a>
            <a href="{{ route('matches.index', ['status' => 'upcoming']) }}" class="{{ $status === 'upcoming' ? 'active' : '' }}">Upcoming</a>
        </div>

        <div class="match-grid">
            @forelse ($matches as $match)
                <a href="{{ route('matches.show', $match) }}" class="match-card">
                    <span class="badge badge-matchno">Match {{ $match->match_number }}</span>
                    <span class="badge badge-{{ $match->status }}">{{ $match->status }}</span>
                    <div class="match-date-pill">{{ $match->match_date->format('d M Y') }}</div>
                    <div class="match-teams">
                        <div class="match-team">
                            <img class="team-logo" src="{{ asset('images/teams/real-challenger-boys.webp') }}" alt="Real Challenger Boys">
                            <div class="match-score">{{ $match->status === 'completed' ? $match->rcb_score : '-' }} <small>({{ $match->status === 'completed' ? $match->rcb_overs : '-' }})</small></div>
                        </div>
                        <span class="vs-badge">vs</span>
                        <div class="match-team right">
                            <div class="match-score">{{ $match->status === 'completed' ? $match->opponent_score : '-' }} <small>({{ $match->status === 'completed' ? $match->opponent_overs : '-' }})</small></div>
                            @if ($match->opponent_logo)
                                <img class="team-logo opponent" src="{{ asset($match->opponent_logo) }}" alt="{{ $match->opponent_name }}">
                            @else
                                <span class="team-logo opponent">{{ mb_strtoupper(mb_substr($match->opponent_name, 0, 2)) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="match-names">
                        <span>REAL CHALLENGER BOYS</span>
                        <span>{{ $match->opponent_name }}</span>
                    </div>
                    @if ($match->status === 'completed' && $match->result_text)
                        <div class="match-footer {{ $match->won ? '' : 'lost' }}">{{ $match->result_text }}</div>
                    @else
                        <div class="match-footer">📍 {{ $match->venue }}</div>
                    @endif
                </a>
            @empty
                <div class="card"><p class="muted">No matches found.</p></div>
            @endforelse
        </div>

        {{ $matches->links() }}
    </div>
@endsection
