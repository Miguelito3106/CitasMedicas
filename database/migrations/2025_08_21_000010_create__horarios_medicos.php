<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('horarios_medicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medico_id')->constrained('medicos')->onDelete('cascade');
            $table->enum('dia_semana', ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado']);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();
            
            // Evitar horarios duplicados para el mismo médico y día
            $table->unique(['medico_id', 'dia_semana']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('horarios_medicos');
    }
};