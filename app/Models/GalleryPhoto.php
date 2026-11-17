<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    protected $fillable = [
        'title',
        'title_bn',
        'image',
        'sort_order',
        'type',
        'video_code',
        'thumbnail',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function scopePhotos(Builder $query): Builder
    {
        return $query->where('type', 'photo');
    }

    public function scopeVideos(Builder $query): Builder
    {
        return $query->where('type', 'video');
    }

    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'bn' && $this->title_bn ? $this->title_bn : ($this->title ?: 'RCB');
    }

    public function getDisplayDateAttribute(): ?string
    {
        return $this->date ? $this->date->translatedFormat('d M Y') : null;
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail ? asset($this->thumbnail) : ($this->image ? asset($this->image) : asset('images/logo.png'));
    }
}
