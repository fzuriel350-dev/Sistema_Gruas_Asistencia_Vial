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
        Schema::table('servicios', function (Blueprint $table) {
            $table->foreignId('tipo_servicio_id')->after('unidad_id')->constrained('tipos_servicio');
            $table->text('descripcion')->after('tipo_servicio_id')->nullable();
            $table->dropColumn('tipo');
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->string('tipo')->after('unidad_id');
            $table->dropForeign(['tipo_servicio_id']);
            $table->dropColumn(['tipo_servicio_id', 'descripcion']);
        });
    }
};
