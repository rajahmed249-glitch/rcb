<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CricketMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'match_number',
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

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed')->orderByDesc('match_date');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', 'upcoming')->orderBy('match_date');
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
