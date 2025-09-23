<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'BloqueConsultorio',
        'NumeroConsultorio',
        'idMedico'
    ];

    // Relación con médico
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'idMedico');
    }

    // Accesor para consultorio completo
    public function getConsultorioCompletoAttribute()
    {
        return "Bloque {$this->BloqueConsultorio} - Consultorio {$this->NumeroConsultorio}";
    }
}