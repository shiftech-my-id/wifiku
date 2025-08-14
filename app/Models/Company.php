<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'owner_name',
        'phone',
        'address',
        'registration_datetime',
        'activation_datetime',
        'activation_code',
        'active',
    ];

    protected $casts = [
        'registration_datetime' => 'datetime',
        'activation_datetime' => 'datetime',
        'active' => 'boolean',
    ];
}
