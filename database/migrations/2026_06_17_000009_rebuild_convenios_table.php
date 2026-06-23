<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('cotizaciones');
        Schema::dropIfExists('convenios');

        Schema::create('convenios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('aseguradora_id')->constrained('aseguradoras');
            $table->string('nombre', 150);
            $table->string('tipo'); // local, foraneo
            $table->decimal('costo_banderazo', 10, 2);
            $table->decimal('costo_km', 10, 2);
            $table->decimal('km_incluidos', 10, 2)->default(0);
            $table->decimal('descuento', 5, 2)->default(0);
            $table->string('cobertura')->default('sin_cobertura');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convenios');

        Schema::create('convenios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('aseguradora');
            $table->string('nivel');
            $table->decimal('descuento', 5, 2)->default(0);
            $table->string('cobertura');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }
};
