<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'name_bn',
        'sort_order',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return trim(($this->name_bn && app()->getLocale() === 'bn' ? $this->name_bn : $this->name));
    }
}
