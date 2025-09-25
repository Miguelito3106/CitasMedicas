<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultorios extends Model
{
    use HasFactory;

    protected $table = 'consultorios';

    protected $fillable = [
        'BloqueConsultorio',
        'NumeroConsultorio',
        'idMedico'
    ];

    public function medico()
    {
        return $this->belongsTo(Medicos::class, 'idMedico');
    }
}