<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Team extends Model
{
    protected $fillable = [
        'name',
        'name_bn',
        'slug',
        'short_name',
        'short_name_bn',
        'logo',
        'location',
        'is_rcb',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_rcb' => 'boolean',
        'status' => 'boolean',
    ];

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'bn' && $this->name_bn ? $this->name_bn : $this->name;
    }

    public function getLogoUrlAttribute(): ?string
    {
        if ($this->logo) {
            return asset($this->logo);
        }

        return $this->is_rcb ? asset('images/teams/real-challenger-boys.webp') : null;
    }

    public function tournaments()
    {
        return $this->belongsToMany(Tournament::class, 'tournament_teams')->withTimestamps();
    }

    public static function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: 'team';
        $original = $slug;
        $i = 2;

        while (self::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}
