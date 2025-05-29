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
            $table->string('ipsable_type')->nullable();
            $table->string('ipsable_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_vendors', function (Blueprint $table) {
            $table->dropColumn('ipsable_type');
            $table->dropColumn('ipsable_id');
        });
    }
};
