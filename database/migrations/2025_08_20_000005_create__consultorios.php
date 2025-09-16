<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('consultorios', function (Blueprint $table) { // ← Nombre corregido
        $table->id();
        $table->string('BloqueConsultorio'); // ← Nombre corregido
        $table->string('NumeroConsultorio')->unique();
        $table->timestamps();
        $table->foreignId('idMedico')->constrained('medicos')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('consultorios'); // ← Nombre corregido
}
};