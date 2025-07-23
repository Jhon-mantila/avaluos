<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('avaluos', function (Blueprint $table) {
            $table->string('auxiliar')->nullable()->after('cliente_id');
            $table->dateTime('fecha_entrega_avaluo')->nullable()->after('auxiliar');
            $table->bigInteger('valor_informe')->nullable()->after('fecha_entrega_avaluo');
        });
    }

    public function down(): void
    {
        Schema::table('avaluos', function (Blueprint $table) {
            $table->dropColumn(['auxiliar', 'fecha_entrega_avaluo', 'valor_informe']);
        });
    }
};