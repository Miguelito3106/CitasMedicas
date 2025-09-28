<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('medicos', function (Blueprint $table) {
            // Agregar campos médicos específicos
            $table->string('licencia_medica')->unique()->nullable();
            $table->string('especialidad')->nullable();
            $table->boolean('disponible')->default(true);
            $table->time('horario_inicio')->default('08:00:00');
            $table->time('horario_fin')->default('17:00:00');
        });
    }

    public function down()
    {
        Schema::table('medicos', function (Blueprint $table) {
            $table->dropColumn([
                'licencia_medica',
                'especialidad', 
                'disponible',
                'horario_inicio',
                'horario_fin'
            ]);
        });
    }
};