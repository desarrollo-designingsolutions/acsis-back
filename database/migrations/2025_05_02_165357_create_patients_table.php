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
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->constrained();
            $table->foreignUuid('tipo_id_pisi_id')->constrained('tipo_id_pisis');
            $table->string('document')->nullable();
            $table->foreignUuid('rips_tipo_usuario_version2_id')->constrained('rips_tipo_usuario_version2s');
            $table->date('birth_date')->nullable();
            $table->foreignUuid('sexo_id')->constrained('sexos');
            $table->foreignUuid('pais_residency_id')->constrained('pais');
            $table->foreignUuid('municipio_residency_id')->constrained('municipios');
            $table->foreignUuid('zona_version2_id')->constrained('zona_version2s');
            $table->boolean('incapacity')->default(false);
            $table->foreignUuid('pais_origin_id')->constrained('pais');
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('first_surname')->nullable();
            $table->string('second_surname')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
