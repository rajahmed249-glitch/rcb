<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerApplication extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'age',
        'preferred_role',
        'message',
    ];
}
