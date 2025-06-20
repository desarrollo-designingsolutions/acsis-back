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
        Schema::table('furips1s', function (Blueprint $table) {

            $table->foreignUuid('mainHospitalizationCupsCode_id')->nullable()->constrained('cups_rips');
            $table->foreignUuid('mainSurgicalProcedureCupsCode_id')->nullable()->constrained('cups_rips');
            $table->foreignUuid('secondarySurgicalProcedureCupsCode_id')->nullable()->constrained('cups_rips');
            $table->string('uciServiceProvided')->nullable();
            $table->string('claimedUciDays')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('furips1s', function (Blueprint $table) {
            $table->dropConstrainedForeignId('mainHospitalizationCupsCode_id');
            $table->dropConstrainedForeignId('mainSurgicalProcedureCupsCode_id');
            $table->dropConstrainedForeignId('secondarySurgicalProcedureCupsCode_id');

            $table->dropColumn('uciServiceProvided')->nullable();
            $table->dropColumn('claimedUciDays')->nullable();
        });
    }
};
