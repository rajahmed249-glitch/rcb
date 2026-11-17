<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchCommentary extends Model
{
    protected $fillable = [
        'match_id',
        'side',
        'over_number',
        'score_at',
        'ball_number',
        'ball_per_run',
        'description',
        'sort_order',
    ];

    public function match()
    {
        return $this->belongsTo(CricketMatch::class, 'match_id');
    }
}
