<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ACTUALIZAR: Métodos de verificación de roles
    public function isPaciente()
    {
        return $this->role === 'paciente';
    }

    public function isDoctor()
    {
        return $this->role === 'doctor';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // NUEVO: Relación con paciente
    public function paciente()
    {
        return $this->hasOne(Pacientes::class, 'user_id');
    }

    // ACTUALIZAR: Relación con médico
    public function medico()
    {
        return $this->hasOne(Medicos::class, 'user_id');
    }
}