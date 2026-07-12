<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('convenios', function (Blueprint $table) {
            $table->foreignId('tipo_servicio_id')->nullable()->after('aseguradora_id')->constrained('tipos_servicio');
        });

        $defaultId = DB::table('tipos_servicio')->where('nombre', 'Arrastre')->value('id');
        if ($defaultId) {
            DB::table('convenios')->whereNull('tipo_servicio_id')->update(['tipo_servicio_id' => $defaultId]);
        }

        Schema::table('convenios', function (Blueprint $table) {
            $table->foreignId('tipo_servicio_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('convenios', function (Blueprint $table) {
            $table->dropForeign(['tipo_servicio_id']);
            $table->dropColumn('tipo_servicio_id');
        });
    }
};
