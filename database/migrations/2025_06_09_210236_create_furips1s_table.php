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
        Schema::create('furips1s', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('invoice_id')->nullable()->constrained();

            // Datos de la reclamación
            $table->string('previousFilingNumber', 10)->nullable();
            $table->string('rgoResponse')->nullable();
            $table->string('consecutiveClaimNumber', 12)->nullable();

            // Datos de la víctima
            $table->string('victimResidenceAddress', 40)->nullable();
            $table->string('victimPhone', 10)->nullable();
            $table->string('victimCondition')->nullable();

            // Datos del sitio del evento
            $table->string('eventNature')->nullable();
            $table->string('otherEventDescription', 25)->nullable();
            $table->string('eventOccurrenceAddress', 40)->nullable();
            $table->string('eventOccurrenceDate')->nullable();
            $table->string('eventOccurrenceTime')->nullable();
            $table->foreignUuid('eventDepartmentCode_id')->nullable()->constrained('departamentos');
            $table->foreignUuid('eventMunicipalityCode_id')->nullable()->constrained('municipios');
            $table->string('eventZone')->nullable();

            // Datos de remisión
            $table->string('referenceType')->nullable();
            $table->string('referralDate')->nullable();
            $table->string('departureTime')->nullable();
            $table->foreignUuid('referringHealthProviderCode_id')->nullable()->constrained('ips_cod_habilitacions');
            $table->string('referringProfessional', 60)->nullable();
            $table->string('referringPersonPosition', 30)->nullable();
            $table->string('admissionDate')->nullable();
            $table->string('admissionTime')->nullable();
            $table->foreignUuid('receivingHealthProviderCode_id')->nullable()->constrained('ips_cod_habilitacions');
            $table->string('receivingProfessional', 60)->nullable();
            $table->string('interinstitutionalTransferAmbulancePlate', 6)->nullable();

            // Datos del vehículo
            $table->string('vehicleBrand', 15)->nullable();
            $table->string('vehiclePlate', 10)->nullable();
            $table->string('vehicleType')->nullable();
            $table->string('sirasFilingNumber', 20)->nullable();
            $table->string('insurerCapExhaustionCharge')->nullable();

            // Datos relacionados con la atención
            $table->string('surgicalProcedureComplexity')->nullable();

            // Datos del propietario
            $table->foreignUuid('ownerDocumentType_id')->nullable()->constrained('tipo_id_pisis');
            $table->string('ownerDocumentNumber', 16)->nullable();
            $table->string('ownerFirstLastName', 40)->nullable();
            $table->string('ownerSecondLastName', 30)->nullable();
            $table->string('ownerFirstName', 20)->nullable();
            $table->string('ownerSecondName', 30)->nullable();
            $table->string('ownerResidenceAddress', 40)->nullable();
            $table->string('ownerResidencePhone', 10)->nullable();
            $table->foreignUuid('ownerResidenceDepartmentCode_id')->nullable()->constrained('departamentos');
            $table->foreignUuid('ownerResidenceMunicipalityCode_id')->nullable()->constrained('municipios');

            // Datos del conductor
            $table->string('driverFirstLastName', 20)->nullable();
            $table->string('driverSecondLastName', 30)->nullable();
            $table->string('driverFirstName', 20)->nullable();
            $table->string('driverSecondName', 30)->nullable();
            $table->foreignUuid('driverDocumentType_id')->nullable()->constrained('tipo_id_pisis');
            $table->string('driverDocumentNumber', 16)->nullable();
            $table->string('driverResidenceAddress', 40)->nullable();
            $table->foreignUuid('driverResidenceDepartmentCode_id')->nullable()->constrained('departamentos');
            $table->foreignUuid('driverResidenceMunicipalityCode_id')->nullable()->constrained('municipios');
            $table->string('driverResidencePhone', 10)->nullable();

            // Transporte y movilización
            $table->string('primaryTransferAmbulancePlate', 6)->nullable();
            $table->string('victimTransportFromEventSite', 40)->nullable();
            $table->string('victimTransportToEnd', 40)->nullable();
            $table->string('transportServiceType')->nullable();
            $table->string('victimPickupZone')->nullable();

            // Datos del médico
            $table->string('doctorFirstLastName', 20)->nullable();
            $table->string('doctorSecondLastName', 30)->nullable();
            $table->string('doctorFirstName', 20)->nullable();
            $table->string('doctorSecondName', 30)->nullable();
            $table->string('doctorRegistrationNumber', 16)->nullable();

            // Amparos que reclama
            $table->string('totalBilledMedicalSurgical', 15)->nullable();
            $table->string('totalClaimedMedicalSurgical', 15)->nullable();
            $table->string('totalBilledTransport', 15)->nullable();
            $table->string('totalClaimedTransport', 15)->nullable();

            // Confirmación servicios habilitados
            $table->string('enabledServicesConfirmation')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('furips1s');
    }
};
