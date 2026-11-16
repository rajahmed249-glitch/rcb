<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'role',
        'jersey_number',
        'photo',
        'is_captain',
        'is_vice_captain',
        'matches_played',
        'runs',
        'wickets',
        'batting_style',
        'bowling_style',
        'bio',
    ];

    protected $casts = [
        'is_captain' => 'boolean',
        'is_vice_captain' => 'boolean',
    ];

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset($this->photo) : null;
    }

    public function getBadgeAttribute(): string
    {
        return match (true) {
            $this->is_captain => 'c',
            $this->is_vice_captain => 'vc',
            default => '',
        };
    }

    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/', trim($this->name));
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= mb_strtoupper(mb_substr($part, 0, 1));
        }

        return $initials ?: 'RCB';
    }
}
