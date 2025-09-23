<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'email',
        'telefono',
        'user_id'
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con consultorios
    public function consultorios()
    {
        return $this->hasMany(Consultorio::class, 'idMedico');
    }

    // Relación con horarios
    public function horarios()
    {
        return $this->hasMany(HorarioMedico::class, 'medico_id');
    }

    // Relación con citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'idMedico');
    }

    // Accesor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }
}