<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('color_secundario', 20)->nullable()->after('color');
            $table->string('tipografia', 100)->default('Inter')->after('color_secundario');
            $table->string('logo_oscuro', 255)->nullable()->after('logo');
            $table->string('favicon', 255)->nullable()->after('logo_oscuro');
            $table->string('telefono_contacto', 30)->nullable()->after('favicon');
            $table->string('email_contacto', 150)->nullable()->after('telefono_contacto');
            $table->string('whatsapp', 30)->nullable()->after('email_contacto');
            $table->string('direccion', 255)->nullable()->after('whatsapp');
            $table->string('sitio_web', 255)->nullable()->after('direccion');
            $table->string('moneda', 10)->default('$')->after('sitio_web');
            $table->string('formato_fecha', 20)->default('d/m/Y')->after('moneda');
            $table->string('zona_horaria', 50)->default('America/Mexico_City')->after('formato_fecha');
            $table->string('idioma', 5)->default('es')->after('zona_horaria');
            $table->string('footer_texto', 255)->nullable()->after('idioma');
            $table->boolean('mostrar_precios')->default(true)->after('footer_texto');
            $table->boolean('notificaciones_habilitadas')->default(true)->after('mostrar_precios');
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'color_secundario', 'tipografia', 'logo_oscuro', 'favicon',
                'telefono_contacto', 'email_contacto', 'whatsapp', 'direccion',
                'sitio_web', 'moneda', 'formato_fecha', 'zona_horaria',
                'idioma', 'footer_texto', 'mostrar_precios', 'notificaciones_habilitadas',
            ]);
        });
    }
};
