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
        Schema::table('newly_borns', function (Blueprint $table) {
            $table->foreignUuid('tipoDocumentoIdentificacion_id')->nullable()->constrained('tipo_id_pisis');
            $table->string('numDocumentoIdentificacion')->nullable();
            $table->string('codPrestador')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('newly_borns', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tipoDocumentoIdentificacion_id');
            $table->dropColumn('numDocumentoIdentificacion');
            $table->dropColumn('codPrestador');
        });
    }
};
