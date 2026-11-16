<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'name_bn',
        'slug',
        'sort_order',
    ];

    public function getDisplayNameAttribute(): string
    {
        return trim(($this->name_bn && app()->getLocale() === 'bn' ? $this->name_bn : $this->name));
    }
}
