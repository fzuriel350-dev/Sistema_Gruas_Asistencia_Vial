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
        Schema::create('aseguradora_tipo_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aseguradora_id')->constrained('aseguradoras')->cascadeOnDelete();
            $table->foreignId('tipo_servicio_id')->constrained('tipos_servicio')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['aseguradora_id', 'tipo_servicio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aseguradora_tipo_servicio');
    }
};
