<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->constrained('empresas')->after('id');
            $table->foreignId('empleado_id')->nullable()->constrained('empleados')->after('empresa_id');
            $table->dropColumn(['phone', 'aseguradora', 'poliza', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropForeign(['empleado_id']);
            $table->dropColumn(['empresa_id', 'empleado_id']);
            $table->string('phone', 20)->nullable()->after('password');
            $table->string('aseguradora')->nullable()->after('phone');
            $table->string('poliza')->nullable()->after('aseguradora');
            $table->string('status')->default('pendiente')->after('poliza');
        });
    }
};
