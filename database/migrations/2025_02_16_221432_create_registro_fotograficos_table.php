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
        Schema::create('registro_fotograficos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('informacion_visita_id');
            $table->foreign('informacion_visita_id')->references('id')->on('informacion_visitas');
            $table->string('foto')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('tipo_foto')->nullable();
            $table->integer('orden')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_fotograficos');
    }
};
