<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorariosMedicos extends Model
{
    use HasFactory;

    protected $table = 'horarios_medicos';

    protected $fillable = [
        'medico_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    public function medico()
    {
        return $this->belongsTo(Medicos::class, 'medico_id');
    }
}