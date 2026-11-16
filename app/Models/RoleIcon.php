<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleIcon extends Model
{
    protected $fillable = [
        'name',
        'name_bn',
        'icon',
        'sort_order',
    ];

    public function getDisplayNameAttribute(): string
    {
        return trim(($this->name_bn && app()->getLocale() === 'bn' ? $this->name_bn : $this->name));
    }

    public function getIsImageAttribute(): bool
    {
        return (bool) preg_match('/\.(webp|png|jpe?g|gif|svg)$/i', (string) $this->icon);
    }
}
