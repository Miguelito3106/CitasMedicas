<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // Cambiar la relación para que sea más estricta
            $table->foreignId('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Agregar campos adicionales para pacientes
            $table->string('direccion')->nullable();
            $table->string('alergias')->nullable();
            $table->string('enfermedades_cronicas')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn(['direccion', 'alergias', 'enfermedades_cronicas']);
        });
    }
};