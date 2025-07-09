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
        Schema::table('registros_fotograficos', function (Blueprint $table) {
            //
            $table->integer('ancho')->nullable()->after('imagen');
            $table->integer('alto')->nullable()->after('ancho');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registros_fotograficos', function (Blueprint $table) {
            //
            $table->dropColumn(['ancho', 'alto']);
        });
    }
};
