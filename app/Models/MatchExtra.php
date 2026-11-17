<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchExtra extends Model
{
    protected $fillable = [
        'match_id',
        'side',
        'byes',
        'leg_byes',
        'wides',
        'no_balls',
        'total',
    ];

    public function match()
    {
        return $this->belongsTo(CricketMatch::class, 'match_id');
    }
}
