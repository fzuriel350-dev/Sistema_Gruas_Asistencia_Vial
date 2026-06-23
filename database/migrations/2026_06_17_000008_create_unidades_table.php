<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->foreignId('operador_id')->nullable()->constrained('operadores');
            $table->string('marca', 50);
            $table->string('tipo', 50);
            $table->integer('año');
            $table->string('placas', 20);
            $table->string('numero_serie', 50)->nullable();
            $table->date('seguro_vencimiento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unidades');
    }
};
