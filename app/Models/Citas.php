<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citas extends Model
{
    protected $table = 'citas';
    protected $fillable = [
        'idMedico',
        'idPaciente',
        'fecha_cita',
        'hora_cita',
        'estado',
        'motivo'
    ];

    public function medico()
    {
        return $this->belongsTo(Medicos::class, 'idMedico');
    }

    public function paciente()
    {
        return $this->belongsTo(Pacientes::class, 'idPaciente');
    }
}