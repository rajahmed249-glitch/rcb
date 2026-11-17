<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchFallOfWicket extends Model
{
    protected $fillable = [
        'match_id',
        'side',
        'player_id',
        'name',
        'score',
        'wicket_no',
        'over',
        'sort_order',
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
