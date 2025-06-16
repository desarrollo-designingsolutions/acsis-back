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
            $table->foreignUuid('doctorIdType_id')->after('victimPickupZone')->nullable()->constrained('tipo_id_pisis');
            $table->string('doctorIdNumber', 16)->after('doctorIdType_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('furips1s', function (Blueprint $table) {
            $table->dropConstrainedForeignId('doctorIdType_id');
            $table->dropColumn('doctorIdNumber', 16);
        });
    }
};
