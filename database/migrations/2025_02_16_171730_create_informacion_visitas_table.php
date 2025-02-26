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
        Schema::create('informacion_visitas', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Clave primaria UUID
            $table->bigInteger('incremental_id')->unsigned()->unique(); // Será autoincremental luego
            $table->uuid('avaluo_id');
            $table->foreign('avaluo_id')->references('id')->on('avaluos');
            $table->uuid('visitador_id');
            $table->foreign('visitador_id')->references('id')->on('visitadores');
            $table->string('celular')->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->datetime('fecha_visita')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE informacion_visitas MODIFY COLUMN incremental_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informacion_visitas');
    }
};
