<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'name_bn',
        'type',
        'parent_id',
        'sort_order',
    ];

    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return trim(($this->name_bn && app()->getLocale() === 'bn' ? $this->name_bn : $this->name));
    }
}
