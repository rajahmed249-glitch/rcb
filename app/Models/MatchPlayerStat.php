<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchPlayerStat extends Model
{
    protected $fillable = [
        'match_id',
        'player_id',
        'fielder_id',
        'bowler_id',
        'side',
        'stat_type',
        'name',
        'sort_order',
        'runs',
        'balls',
        'fours',
        'sixes',
        'dismissal',
        'overs',
        'maidens',
        'wickets',
        'wides',
        'noballs',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function match()
    {
        return $this->belongsTo(CricketMatch::class, 'match_id');
    }
}
