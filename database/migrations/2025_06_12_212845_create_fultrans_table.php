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
        Schema::create('fultrans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')->nullable()->constrained();

            $table->string('previousRecordNumber', 10)->nullable();
            $table->string('rgResponse')->nullable();
            $table->string('firstLastNameClaimant', 20)->nullable();
            $table->string('secondLastNameClaimant', 30)->nullable();
            $table->string('firstNameClaimant', 20)->nullable();
            $table->string('secondNameClaimant', 30)->nullable();
            $table->foreignUuid('claimantIdType_id')->nullable()->constrained('tipo_id_pisis');
            $table->string('claimantIdNumber', 16)->nullable();
            $table->string('vehicleServiceType')->nullable();
            $table->string('vehiclePlate', 10)->nullable();
            $table->string('claimantAddress', 40)->nullable();
            $table->string('claimantPhone', 10)->nullable();
            $table->foreignUuid('claimantDepartmentCode_id')->nullable()->constrained('departamentos');
            $table->foreignUuid('claimantMunicipalityCode_id')->nullable()->constrained('municipios');
            $table->string('victimGender')->nullable();
            $table->string('eventType')->nullable();
            $table->string('pickupAddress', 40)->nullable();
            $table->foreignUuid('pickupDepartmentCode_id')->nullable()->constrained('departamentos');
            $table->foreignUuid('pickupMunicipalityCode_id')->nullable()->constrained('municipios');
            $table->string('pickupZone')->nullable();
            $table->string('transferDate', 10)->nullable();
            $table->string('transferTime')->nullable();
            $table->foreignUuid('transferPickupDepartmentCode_id')->nullable()->constrained('departamentos');
            $table->foreignUuid('transferPickupMunicipalityCode_id')->nullable()->constrained('municipios');
            $table->string('victimCondition')->nullable();
            $table->string('involvedVehicleType')->nullable();
            $table->string('involvedVehiclePlate', 10)->nullable();
            $table->string('insurerCode', 6)->nullable();
            $table->string('sirasRecordNumber', 20)->nullable();
            $table->string('billedValue', 15)->nullable();
            $table->string('claimedValue', 15)->nullable();
            $table->string('serviceEnabledIndication')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fultrans');
    }
};
