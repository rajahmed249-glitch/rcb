<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    protected $fillable = [
        'visit_date',
        'count',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public static function recordToday(): void
    {
        try {
            self::query()->updateOrCreate(
                ['visit_date' => now()->toDateString()],
                ['count' => 0]
            )->increment('count');
        } catch (\Throwable) {
        }
    }

    public static function todayCount(): int
    {
        try {
            return (int) (self::query()->where('visit_date', now()->toDateString())->value('count') ?? 0);
        } catch (\Throwable) {
            return 0;
        }
    }

    public static function totalCount(): int
    {
        try {
            return (int) (self::query()->sum('count') ?? 0);
        } catch (\Throwable) {
            return 0;
        }
    }
}
