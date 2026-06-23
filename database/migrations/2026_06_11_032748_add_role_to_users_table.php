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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('cliente')->after('password');
            $table->string('phone', 20)->nullable()->after('role');
            $table->string('aseguradora')->nullable()->after('phone');
            $table->string('poliza')->nullable()->after('aseguradora');
            $table->string('status')->default('pendiente')->after('poliza');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'aseguradora', 'poliza', 'status']);
        });
    }
};
