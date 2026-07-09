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
            $table->unsignedBigInteger('operador_id')->nullable()->change();
            $table->unsignedBigInteger('unidad_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->unsignedBigInteger('operador_id')->nullable(false)->change();
            $table->unsignedBigInteger('unidad_id')->nullable(false)->change();
        });
    }
};
