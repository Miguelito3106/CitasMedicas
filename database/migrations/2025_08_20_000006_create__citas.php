<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idPaciente')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('idMedico')->constrained('medicos')->onDelete('cascade');
            $table->date('fecha_cita');
            $table->time('hora_cita');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'atendida'])->default('pendiente');
            $table->text('motivo')->nullable();
            $table->timestamps();
            
            // Índices para mejor performance
            $table->index(['fecha_cita', 'hora_cita']);
            $table->index(['estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('citas');
    }
};