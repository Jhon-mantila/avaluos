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
        Schema::create('registros_fotograficos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('plantilla_id');
            $table->foreign('plantilla_id')->references('id')->on('plantillas');
            $table->string('imagen')->nullable();
            $table->text('title')->nullable();
            $table->string('tipo')->nullable();
            $table->integer('orden')->nullable();
            $table->string('pagina')->nullable();
            $table->string('posicion')->nullable();
            $table->uuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_fotograficos');
    }
};
