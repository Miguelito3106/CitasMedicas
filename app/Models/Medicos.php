<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicos extends Model
{
    use HasFactory;

    protected $table = 'medicos'; // Especificar tabla explícitamente

    protected $fillable = [
        'nombre',
        'apellido',
        'documento',
        'email',
        'telefono',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function consultorios()
    {
        return $this->hasMany(Consultorios::class, 'idMedico');
    }

    public function horarios()
    {
        return $this->hasMany(HorariosMedicos::class, 'medico_id');
    }

    public function citas()
    {
        return $this->hasMany(Citas::class, 'idMedico');
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }
}