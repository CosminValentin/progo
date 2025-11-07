<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'dni',
        'first_name',
        'last_name1',
        'last_name2',
        'birth_date',
        'gender',
        'education_level',
        'eu_resident',
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date'        => 'date',
        'eu_resident'       => 'boolean',
    ];
}
