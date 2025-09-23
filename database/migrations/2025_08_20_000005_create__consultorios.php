<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('consultorios', function (Blueprint $table) {
            $table->id();
            $table->string('BloqueConsultorio');
            $table->string('NumeroConsultorio')->unique();
            $table->foreignId('idMedico')->constrained('medicos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('consultorios');
    }
};