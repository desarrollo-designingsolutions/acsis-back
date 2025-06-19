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
        Schema::table('fultrans', function (Blueprint $table) {
            $table->string('ipsName')->nullable();
            $table->string('ipsNit')->nullable();
            $table->string('ipsAddress')->nullable();
            $table->string('ipsPhone')->nullable();
            $table->foreignUuid('ipsReceptionHabilitationCode_id')->nullable()->constrained('ips_cod_habilitacions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fultrans', function (Blueprint $table) {
            $table->dropColumn('ipsName');
            $table->dropColumn('ipsNit');
            $table->dropColumn('ipsAddress');
            $table->dropColumn('ipsPhone');
            $table->dropConstrainedForeignId('ipsReceptionHabilitationCode_id');
        });
    }
};
