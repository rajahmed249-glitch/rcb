<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'team_id',
        'name',
        'short_name',
        'role',
        'date_of_birth',
        'role_icon_id',
        'district_id',
        'thana_id',
        'village',
        'post_office',
        'religion',
        'blood_group',
        'nationality',
        'debut',
        'player_tag',
        'status',
        'frontend_show',
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
        'name_bn',
        'bio_bn',
    ];

    protected $casts = [
        'is_captain' => 'boolean',
        'is_vice_captain' => 'boolean',
        'date_of_birth' => 'date',
        'debut' => 'date',
        'status' => 'boolean',
        'frontend_show' => 'boolean',
    ];

    public function getRoleIconPathAttribute(): ?string
    {
        if ($this->roleIcon && $this->roleIcon->is_image) {
            return asset($this->roleIcon->icon);
        }

        $map = [
            'batsman' => 'images/role-icons/batsman.webp',
            'batting' => 'images/role-icons/batsman.webp',
            'bowler' => 'images/role-icons/bowler.webp',
            'bowling' => 'images/role-icons/bowler.webp',
            'all-rounder' => 'images/role-icons/allrounder.webp',
            'allrounder' => 'images/role-icons/allrounder.webp',
            'alrounder' => 'images/role-icons/allrounder.webp',
            'wicketkeeper' => 'images/role-icons/wicket-keeper.webp',
            'wicket-keeper' => 'images/role-icons/wicket-keeper.webp',
            'wicket keeper' => 'images/role-icons/wicket-keeper.webp',
        ];

        $key = strtolower(trim((string) $this->role));

        $path = $map[$key] ?? null;

        return $path ? asset($path) : null;
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function roleIcon()
    {
        return $this->belongsTo(RoleIcon::class);
    }

    public function district()
    {
        return $this->belongsTo(Location::class, 'district_id');
    }

    public function thana()
    {
        return $this->belongsTo(Location::class, 'thana_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'bn' && $this->name_bn ? $this->name_bn : $this->name;
    }

    public function getDisplayRoleAttribute(): string
    {
        if (! $this->role) {
            return '';
        }

        $key = 'site.roles.' . str_replace(['-', ' '], '_', strtolower($this->role));

        return __($key) === $key ? $this->role : __($key);
    }

    public function getDisplayBioAttribute(): ?string
    {
        return app()->getLocale() === 'bn' && $this->bio_bn ? $this->bio_bn : $this->bio;
    }

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
