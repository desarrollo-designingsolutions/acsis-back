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
        Schema::create('other_services', function (Blueprint $table) {
            $table->uuid("id")->primary();

            $table->string("numAutorizacion");
            $table->string("idMIPRES");
            $table->string("fechaSuministroTecnologia");
            $table->foreignUuid("tipoOS_id")->constrained("tipo_otros_servicios");
            $table->string("codTecnologiaSalud");
            $table->string("nomTecnologiaSalud");
            $table->string("cantidadOS");
            $table->string("vrUnitOS");
            $table->string("valorPagoModerador");
            $table->string("vrServicio");
            $table->foreignUuid("conceptoRecaudo_id")->constrained("concepto_recaudos");

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_services');
    }
};
