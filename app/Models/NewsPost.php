<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsPost extends Model
{
    protected $fillable = [
        'title',
        'title_bn',
        'slug',
        'excerpt',
        'excerpt_bn',
        'content',
        'content_bn',
        'image',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'bn' && $this->title_bn ? $this->title_bn : $this->title;
    }

    public function getDisplayExcerptAttribute(): ?string
    {
        return app()->getLocale() === 'bn' && $this->excerpt_bn ? $this->excerpt_bn : $this->excerpt;
    }

    public function getDisplayContentAttribute(): ?string
    {
        return app()->getLocale() === 'bn' && $this->content_bn ? $this->content_bn : $this->content;
    }
}
