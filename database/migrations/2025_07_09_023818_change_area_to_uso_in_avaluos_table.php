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
        Schema::table('avaluos', function (Blueprint $table) {
            $table->dropColumn('area');
            $table->string('uso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('avaluos', function (Blueprint $table) {
            $table->dropColumn('uso');
            $table->integer('area')->nullable();
        });
    }
};
