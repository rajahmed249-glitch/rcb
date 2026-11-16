<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CricketMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'match_number',
        'opponent_name',
        'opponent_short_name',
        'venue',
        'match_date',
        'status',
        'format',
        'rcb_score',
        'rcb_overs',
        'opponent_score',
        'opponent_overs',
        'result_text',
        'won',
    ];

    protected $casts = [
        'match_date' => 'datetime',
        'won' => 'boolean',
    ];

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed')->orderByDesc('match_date');
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('status', 'upcoming')->orderBy('match_date');
    }

    public function getResultLabelAttribute(): string
    {
        if ($this->status !== 'completed') {
            return 'Upcoming';
        }

        return $this->result_text ?: 'Result pending';
    }
}
