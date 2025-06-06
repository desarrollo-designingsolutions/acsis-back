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
        Schema::table('medicines', function (Blueprint $table) {
            $table->string('codTecnologiaSaludable_type')->after('codTecnologiaSalud');
            $table->renameColumn('codTecnologiaSalud', 'codTecnologiaSaludable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn('codTecnologiaSaludable_type');
            $table->renameColumn('codTecnologiaSalud_id', 'codTecnologiaSalud');
        });
    }
};
