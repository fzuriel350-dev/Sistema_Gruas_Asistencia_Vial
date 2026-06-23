<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('aseguradora_id')->constrained('aseguradoras');
            $table->foreignId('tipo_servicio_id')->constrained('tipos_servicio');
            $table->string('folio', 50)->unique();
            $table->text('origen');
            $table->text('destino');
            $table->decimal('distancia_km', 10, 2);
            $table->integer('tiempo_estimado');
            $table->string('tipo_ruta'); // local, foraneo
            $table->decimal('costo_banderazo', 10, 2);
            $table->decimal('costo_km', 10, 2);
            $table->decimal('km_excedente', 10, 2)->default(0);
            $table->decimal('costo_total', 12, 2);

            $table->string('no_poliza')->nullable();
            $table->string('marca');
            $table->string('modelo');
            $table->string('color')->nullable();
            $table->string('placas');

            $table->boolean('con_peaje')->default(false);
            $table->integer('num_casetas')->default(0);
            $table->decimal('costo_casetas', 10, 2)->default(0);
            $table->decimal('costo_kilometraje', 10, 2)->default(0);
            $table->decimal('extras', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('iva', 10, 2)->default(0);
            $table->string('cobertura')->default('sin_cobertura');
            $table->foreignId('convenio_id')->nullable()->constrained('convenios');
            $table->decimal('descuento_porcentaje', 5, 2)->default(0);
            $table->decimal('descuento_monto', 10, 2)->default(0);
            $table->text('notas')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->string('estatus')->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');

        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->foreignId('cliente_id')->constrained('users');
            $table->string('aseguradora');
            $table->string('no_poliza')->nullable();
            $table->string('marca');
            $table->string('modelo');
            $table->string('color')->nullable();
            $table->string('placas');
            $table->string('origen');
            $table->string('destino');
            $table->string('tipo_servicio');
            $table->decimal('distancia_km', 8, 2);
            $table->integer('tiempo_min');
            $table->boolean('con_peaje')->default(false);
            $table->integer('num_casetas')->default(0);
            $table->decimal('costo_casetas', 10, 2)->default(0);
            $table->decimal('banderazo', 10, 2)->default(500.00);
            $table->decimal('costo_km', 10, 2)->default(120.00);
            $table->decimal('costo_kilometraje', 10, 2)->default(0);
            $table->decimal('extras', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('iva', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('cobertura')->default('sin_cobertura');
            $table->foreignId('convenio_id')->nullable()->constrained('convenios');
            $table->decimal('descuento_porcentaje', 5, 2)->default(0);
            $table->decimal('descuento_monto', 10, 2)->default(0);
            $table->text('notas')->nullable();
            $table->string('estado')->default('borrador');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }
};
