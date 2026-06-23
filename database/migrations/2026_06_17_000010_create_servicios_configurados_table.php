<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicios_configurados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('tipo_servicio_id')->constrained('tipos_servicio');
            $table->string('nombre', 150);
            $table->string('tipo'); // local, foraneo
            $table->decimal('costo_banderazo', 10, 2);
            $table->decimal('costo_km', 10, 2);
            $table->boolean('activo')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicios_configurados');
    }
};
