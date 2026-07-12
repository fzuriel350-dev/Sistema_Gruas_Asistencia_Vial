<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->decimal('cargos_extras', 10, 2)->default(0)->after('costo_final_real');
            $table->text('motivo_cargos_extras')->nullable()->after('cargos_extras');
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn(['cargos_extras', 'motivo_cargos_extras']);
        });
    }
};
