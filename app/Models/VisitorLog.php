<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_hash',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public static function record(string $ipHash): void
    {
        try {
            $today = now()->toDateString();

            $exists = self::query()
                ->where('ip_hash', $ipHash)
                ->whereDate('visit_date', $today)
                ->exists();

            if (! $exists) {
                self::create([
                    'ip_hash' => $ipHash,
                    'visit_date' => $today,
                ]);
            }
        } catch (\Throwable) {
        }
    }

    public static function todayCount(): int
    {
        try {
            return self::query()
                ->whereDate('visit_date', now()->toDateString())
                ->distinct('ip_hash')
                ->count('ip_hash');
        } catch (\Throwable) {
            return 0;
        }
    }

    public static function totalCount(): int
    {
        try {
            return self::query()->distinct('ip_hash')->count('ip_hash');
        } catch (\Throwable) {
            return 0;
        }
    }
}
