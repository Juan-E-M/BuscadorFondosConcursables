<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone_number',
        'work_institution',
        'college_institution',
        'academic_degree',
        'birth_date',
        'password',
        'role_id',
    ];

    public function role()
    { return $this->belongsTo(Role::class); }

    public function hasRole($roleName)
    { return $this->role->name === $roleName; }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
