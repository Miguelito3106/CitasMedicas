<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacientes extends Model
{
    use HasFactory;

    protected $table = 'pacientes';
    
    protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'fecha_nacimiento',
        'genero',
        'telefono'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    public function citas()
    {
        return $this->hasMany(Citas::class, 'idPaciente');
    }

    public function medicos()
    {
        return $this->belongsToMany(Medicos::class, 'citas', 'idPaciente', 'idMedico');
    }
}