<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerApplication extends Model
{
    protected $fillable = [
        'photo',
        'name',
        'phone',
        'email',
        'age',
        'dob',
        'preferred_role',
        'jersey_size',
        'message',
        'present_village',
        'present_district_id',
        'present_thana_id',
        'present_post_office',
        'permanent_village',
        'permanent_district_id',
        'permanent_thana_id',
        'permanent_post_office',
        'nationality',
        'religion',
        'blood_group',
        'height',
        'facebook_id',
    ];

    protected $casts = [
        'dob' => 'datetime',
    ];
}
