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
        Schema::create('avaluo_contacto', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('avaluo_id');
            $table->uuid('contacto_id');
            $table->datetime('fecha_asignacion')->nullable(); // 🔄 Aquí la mueves
            $table->text('observaciones')->nullable();        // 🔄 Aquí también
            $table->timestamps();

            // Relaciones con claves foráneas
            $table->foreign('avaluo_id')->references('id')->on('avaluos')->onDelete('cascade');
            $table->foreign('contacto_id')->references('id')->on('contactos')->onDelete('cascade');

            // Evitar duplicados
            //$table->unique(['avaluo_id', 'contacto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaluo_contacto');
    }
};
