<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('cotizacion_id')->constrained('cotizaciones');
            $table->foreignId('operador_id')->constrained('operadores');
            $table->foreignId('unidad_id')->constrained('unidades');
            $table->string('tipo'); // local, foraneo
            $table->string('estado'); // asignado, en_proceso, finalizado, cancelado
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
