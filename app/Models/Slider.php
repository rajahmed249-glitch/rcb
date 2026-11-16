<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'title_bn',
        'image',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getDisplayTitleAttribute(): string
    {
        return trim(($this->title_bn && app()->getLocale() === 'bn' ? $this->title_bn : $this->title) ?: '');
    }
}
