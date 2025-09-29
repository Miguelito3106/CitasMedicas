<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // SOLUCIÓN SIMPLE: Solo agregar los campos nuevos y evitar el problema de la constraint
        Schema::table('pacientes', function (Blueprint $table) {
            // Solo agregar los campos nuevos que no causan conflictos
            if (!Schema::hasColumn('pacientes', 'direccion')) {
                $table->string('direccion')->nullable();
            }
            
            if (!Schema::hasColumn('pacientes', 'alergias')) {
                $table->string('alergias')->nullable();
            }
            
            if (!Schema::hasColumn('pacientes', 'enfermedades_cronicas')) {
                $table->string('enfermedades_cronicas')->nullable();
            }
            
            // NO tocar la relación user_id para evitar conflictos
        });
    }

    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // Solo eliminar los campos que agregamos
            if (Schema::hasColumn('pacientes', 'direccion')) {
                $table->dropColumn('direccion');
            }
            
            if (Schema::hasColumn('pacientes', 'alergias')) {
                $table->dropColumn('alergias');
            }
            
            if (Schema::hasColumn('pacientes', 'enfermedades_cronicas')) {
                $table->dropColumn('enfermedades_cronicas');
            }
        });
    }
};