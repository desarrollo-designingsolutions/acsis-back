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
        Schema::table('hospitalizations', function (Blueprint $table) {
            $table->foreignUuid('codigo_hospitalizacion_id')->nullable()->comment('Este campo no existe en el json, solo es para db')->constrained('cups_rips');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitalizations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('codigo_hospitalizacion_id');
        });
    }
};
