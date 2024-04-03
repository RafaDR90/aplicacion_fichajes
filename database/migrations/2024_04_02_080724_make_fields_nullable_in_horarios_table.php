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
        Schema::table('horarios', function (Blueprint $table) {
            $table->time('hora_entrada')->nullable()->change();
            $table->time('hora_salida')->nullable()->change();
            $table->time('descanso_salida')->nullable()->change();
            $table->time('descanso_entrada')->nullable()->change();
            $table->integer('libre')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->time('hora_entrada')->change();
            $table->time('hora_salida')->change();
            $table->time('descanso_salida')->change();
            $table->time('descanso_entrada')->change();
            $table->integer('libre')->change();
        });
    }
};
