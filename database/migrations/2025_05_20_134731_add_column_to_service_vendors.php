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
        Schema::table('service_vendors', function (Blueprint $table) {
            $table->foreignUuid('ips_cod_habilitacion_id')->nullable()->constrained('ips_cod_habilitacions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_vendors', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ips_cod_habilitacion_id');
        });
    }
};
