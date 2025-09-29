<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'role',
        'especialidad',
        // telefono y fecha_nacimiento son opcionales ahora
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'fecha_nacimiento' => 'date',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isMedico()
    {
        return $this->role === 'medico';
    }

    public function isPaciente()
    {
        return $this->role === 'paciente';
    }

    public function citasComoPaciente()
    {
        return $this->hasMany(Citas::class, 'paciente_id');
    }

    public function citasComoMedico()
    {
        return $this->hasMany(Citas::class, 'medico_id');
    }
}