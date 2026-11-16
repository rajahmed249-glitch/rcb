@extends('layouts.app')

@section('title', 'Match ' . $match->match_number . ' vs ' . $match->opponent_name)

@section('content')
    <div class="container">
        <div class="page-head">
            <h1>Match {{ $match->match_number }} — RCB vs {{ $match->opponent_name }}</h1>
            <p>{{ $match->match_date->format('d M Y, h:i A') }} · {{ $match->format }} · {{ $match->venue }}</p>
        </div>

        <div class="card match-card" style="max-width: 720px;">
            <div class="match-meta">
                <span class="badge badge-{{ $match->status }}">{{ $match->status }}</span>
            </div>
            <div class="scoreboard">
                <div class="team">
                    REAL CHALLENGER BOYS
                    <div class="score">{{ $match->rcb_score ?? '-' }} <small>({{ $match->rcb_overs ?? '-' }})</small></div>
                </div>
                <div class="vs">vs</div>
                <div class="team">
                    {{ $match->opponent_name }}
                    <div class="score">{{ $match->opponent_score ?? '-' }} <small>({{ $match->opponent_overs ?? '-' }})</small></div>
                </div>
            </div>
            @if ($match->result_text)
                <p class="result-line {{ $match->won ? '' : 'lost' }}">{{ $match->result_text }}</p>
            @endif
        </div>

        <p style="margin-top: 2rem;"><a href="{{ route('matches.index') }}" class="btn btn-outline">← Back to Matches</a></p>
    </div>
@endsection
