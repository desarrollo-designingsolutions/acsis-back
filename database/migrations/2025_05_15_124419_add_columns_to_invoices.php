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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('status_xml')->nullable();
            $table->string('path_xml')->nullable()->comment('ruta del archivo xml');
            $table->json('validationXml')->nullable()->comment('errores de validacion XML');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('status_xml');
            $table->dropColumn('path_xml');
            $table->dropColumn('validationXml');
        });
    }
};
