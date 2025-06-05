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
        Schema::table('ips_no_reps', function (Blueprint $table) {
            $table->string('codigo')->nullable()->change();
            $table->string('nombre')->nullable()->change();
            $table->string('descripcion')->nullable()->change();
            $table->string('habilitado')->nullable()->change();
            $table->string('aplicacion')->nullable()->change();
            $table->string('isStandardGEL')->nullable()->change();
            $table->string('isStandardMSPS')->nullable()->change();
            $table->string('telefono')->nullable()->change();
            $table->string('gerente')->nullable()->change();
            $table->string('regimen')->nullable()->change();
            $table->string('codDepartamento')->nullable()->change();
            $table->string('departamento')->nullable()->change();
            $table->string('codMunicipio')->nullable()->change();
            $table->string('municipio')->nullable()->change();
            $table->string('tipoIPS')->nullable()->change();
            $table->string('nivelAtencion')->nullable()->change();
            $table->string('nit')->nullable()->change();
            $table->string('valorRegistro')->nullable()->change();
            $table->string('usuarioResponsable')->nullable()->change();
            $table->string('fecha_actualizacion')->nullable()->change();
            $table->string('isPublicPrivate')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
