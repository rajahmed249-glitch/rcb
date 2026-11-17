<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tournament extends Model
{
    protected $fillable = [
        'name',
        'name_bn',
        'slug',
        'season',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getDisplayNameAttribute(): string
    {
        return app()->getLocale() === 'bn' && $this->name_bn ? $this->name_bn : $this->name;
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'tournament_teams')->withTimestamps();
    }

    public static function uniqueSlug(string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base) ?: 'tournament';
        $original = $slug;
        $i = 2;

        while (self::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $original . '-' . $i++;
        }

        return $slug;
    }
}
