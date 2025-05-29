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
        Schema::table('invoice_soats', function (Blueprint $table) {
            $table->foreignUuid('insurance_statuse_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_soats', function (Blueprint $table) {
            $table->dropConstrainedForeignId('insurance_statuse_id');
        });
    }
};
