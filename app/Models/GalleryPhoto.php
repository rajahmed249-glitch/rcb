<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryPhoto extends Model
{
    protected $fillable = [
        'title',
        'title_bn',
        'image',
        'sort_order',
    ];

    public function getDisplayTitleAttribute(): string
    {
        return app()->getLocale() === 'bn' && $this->title_bn ? $this->title_bn : ($this->title ?: 'RCB');
    }
}
