<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'role_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
