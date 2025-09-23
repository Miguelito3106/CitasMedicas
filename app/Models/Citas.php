<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'idPaciente',
        'idMedico',
        'fecha_cita',
        'hora_cita',
        'estado',
        'motivo'
    ];

    protected $casts = [
        'fecha_cita' => 'date',
        'hora_cita' => 'datetime:H:i'
    ];

    // Relación con paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'idPaciente');
    }

    // Relación con médico
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'idMedico');
    }

    // Scope para citas pendientes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    // Scope para citas de hoy
    public function scopeDeHoy($query)
    {
        return $query->where('fecha_cita', today());
    }

    // Accesor para fecha y hora completa
    public function getFechaHoraCompletaAttribute()
    {
        return "{$this->fecha_cita->format('d/m/Y')} a las {$this->hora_cita}";
    }
}