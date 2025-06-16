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
        Schema::create('furips2s', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')->nullable()->constrained();

            $table->string('consecutiveNumberClaim')->nullable();
            $table->string('serviceType')->nullable();
            $table->string('serviceCode_type')->nullable();
            $table->string('serviceCode_id')->nullable();
            $table->text('serviceDescription')->nullable();
            $table->string('serviceQuantity')->nullable();
            $table->decimal('serviceValue', 15, 2)->nullable();
            $table->decimal('totalFactoryValue', 15, 0)->nullable();
            $table->decimal('totalClaimedValue', 15, 0)->nullable();
            $table->string('victimResidenceAddress', 40)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('furips2s');
    }
};
