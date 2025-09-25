<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacientes extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'fecha_nacimiento',
        'telefono',
        'genero',
        'user_id'
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con citas
    public function citas()
    {
        return $this->hasMany(Citas::class, 'idPaciente');
    }

    // Accesor para nombre completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    // Accesor para edad
    public function getEdadAttribute()
    {
        return $this->fecha_nacimiento ? now()->diffInYears($this->fecha_nacimiento) : null;
    }
}