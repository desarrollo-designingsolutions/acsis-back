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
        Schema::table('urgencies', function (Blueprint $table) {
            $table->renameColumn('condicionDestinoUsuarioEgreso', 'condicionDestinoUsuarioEgreso_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('urgencies', function (Blueprint $table) {
            $table->renameColumn('condicionDestinoUsuarioEgreso_id', 'condicionDestinoUsuarioEgreso');
        });
    }
};
