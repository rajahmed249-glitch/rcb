<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CricketMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'match_number',
        'match_name',
        'match_time',
        'match_info',
        'tournament_id',
        'opponent_team_id',
        'opponent_name',
        'opponent_name_bn',
        'opponent_short_name',
        'opponent_logo',
        'venue',
        'match_date',
        'status',
        'format',
        'rcb_score',
        'rcb_overs',
        'rcb_extras',
        'opponent_score',
        'opponent_overs',
        'opponent_extras',
        'result_text',
        'won',
        'toss_winner_id',
        'toss_elected',
        'winner_team_id',
        'player_of_match_id',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'won' => 'boolean',
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function opponentTeam()
    {
        return $this->belongsTo(Team::class, 'opponent_team_id');
    }

    public function squad()
    {
        return $this->belongsToMany(Player::class, 'match_squad', 'match_id', 'player_id')->withTimestamps();
    }

    public function stats()
    {
        return $this->hasMany(MatchPlayerStat::class, 'match_id')->orderBy('sort_order')->orderBy('id');
    }

    public function fow()
    {
        return $this->hasMany(MatchFallOfWicket::class, 'match_id')->orderBy('sort_order')->orderBy('id');
    }

    public function commentary()
    {
        return $this->hasMany(MatchCommentary::class, 'match_id')->orderBy('sort_order')->orderBy('id');
    }

    public function tossWinner()
    {
        return $this->belongsTo(Team::class, 'toss_winner_id');
    }

    public function matchExtras()
    {
        return $this->hasMany(MatchExtra::class, 'match_id');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed')->orderByDesc('match_number')->orderByDesc('match_date');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', 'upcoming')->orderByDesc('match_number')->orderByDesc('match_date');
    }

    public function getDisplayOpponentNameAttribute(): string
    {
        return app()->getLocale() === 'bn' && $this->opponent_name_bn ? $this->opponent_name_bn : $this->opponent_name;
    }

    public function getResultLabelAttribute(): string
    {
        if ($this->status !== 'completed') {
            return 'Upcoming';
        }

        return $this->result_text ?: 'Result pending';
    }
}
