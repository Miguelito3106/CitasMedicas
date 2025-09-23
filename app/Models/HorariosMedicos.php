<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioMedico extends Model
{
    use HasFactory;

    protected $fillable = [
        'medico_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i'
    ];

    // Relación con médico
    public function medico()
    {
        return $this->belongsTo(Medico::class);
    }

    // Scope para un día específico
    public function scopeDelDia($query, $dia)
    {
        return $query->where('dia_semana', $dia);
    }

    // Accesor para horario formateado
    public function getHorarioFormateadoAttribute()
    {
        return "{$this->hora_inicio} - {$this->hora_fin}";
    }
}