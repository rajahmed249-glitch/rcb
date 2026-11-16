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
                <div class="card match-card">
                    <div class="match-meta">
                        <span class="badge badge-matchno">Match {{ $match->match_number }}</span>
                        <span class="badge badge-{{ $match->status }}">{{ $match->status }}</span>
                        <span>{{ $match->match_date->format('d M Y') }}</span>
                    </div>
                    <div class="scoreboard">
                        <div class="team">
                            REAL CHALLENGER BOYS
                            <div class="score">{{ $match->status === 'completed' ? $match->rcb_score : '-' }} <small>({{ $match->status === 'completed' ? $match->rcb_overs : '-' }})</small></div>
                        </div>
                        <div class="vs">vs</div>
                        <div class="team">
                            {{ $match->opponent_name }}
                            <div class="score">{{ $match->status === 'completed' ? $match->opponent_score : '-' }} <small>({{ $match->status === 'completed' ? $match->opponent_overs : '-' }})</small></div>
                        </div>
                    </div>
                    @if ($match->status === 'completed' && $match->result_text)
                        <p class="result-line {{ $match->won ? '' : 'lost' }}">{{ $match->result_text }}</p>
                    @elseif ($match->venue)
                        <p class="venue-line">📍 {{ $match->venue }}</p>
                    @endif
                    <a href="{{ route('matches.show', $match) }}" class="read-more">Match Details →</a>
                </div>
            @empty
                <div class="card"><p class="muted">No matches found.</p></div>
            @endforelse
        </div>

        {{ $matches->links() }}
    </div>
@endsection
