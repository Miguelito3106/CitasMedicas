<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicos extends Model
{
    protected $table = 'medicos';
    protected $fillable = [
        'nombre',
        'apellido', 
        'documento',
        'email',
        'telefono'
    ];
    
    public function citas()
    {
        return $this->hasMany(Citas::class, 'idMedico');
    }
    
    public function consultorio()
    {
        return $this->hasOne(Consultorios::class, 'idMedico');    
    }
    
    public function horariosMedicos()
    {
        return $this->hasMany(HorariosMedicos::class, 'medico_id');
    }
}