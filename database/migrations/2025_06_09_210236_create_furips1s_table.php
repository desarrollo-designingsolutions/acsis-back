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
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('previous_filing_number')->nullable();
            $table->string('rgo_response')->nullable();
            $table->string('consecutive_claim_number')->nullable();
            $table->string('victim_residence_address')->nullable();
            $table->string('victim_phone')->nullable();
            $table->string('victim_condition')->nullable();
            $table->string('event_nature')->nullable();
            $table->string('other_event_description')->nullable();
            $table->string('event_occurrence_address')->nullable();
            $table->string('event_occurrence_date')->nullable();
            $table->string('event_occurrence_time')->nullable();
            $table->string('event_department_code')->nullable();
            $table->string('event_municipality_code')->nullable();
            $table->string('event_zone')->nullable();
            $table->string('reference_type')->nullable();
            $table->string('referral_date')->nullable();
            $table->string('departure_time')->nullable();
            $table->string('referring_health_provider_code')->nullable();
            $table->string('referring_professional')->nullable();
            $table->string('referring_person_position')->nullable();
            $table->string('admission_date')->nullable();
            $table->string('admission_time')->nullable();
            $table->string('receiving_health_provider_code')->nullable();
            $table->string('receiving_professional')->nullable();
            $table->string('interinstitutional_transfer_ambulance_plate')->nullable();
            $table->string('vehicle_brand')->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->string('vehicle_type')->nullable();
            $table->string('siras_filing_number')->nullable();
            $table->string('insurer_cap_exhaustion_charge')->nullable();
            $table->string('surgical_procedure_complexity')->nullable();
            $table->string('owner_document_type')->nullable();
            $table->string('owner_document_number')->nullable();
            $table->string('owner_first_last_name')->nullable();
            $table->string('owner_second_last_name')->nullable();
            $table->string('owner_first_name')->nullable();
            $table->string('owner_second_name')->nullable();
            $table->string('owner_residence_address')->nullable();
            $table->string('owner_residence_phone')->nullable();
            $table->string('owner_residence_department_code')->nullable();
            $table->string('owner_residence_municipality_code')->nullable();
            $table->string('driver_first_last_name')->nullable();
            $table->string('driver_second_last_name')->nullable();
            $table->string('driver_first_name')->nullable();
            $table->string('driver_second_name')->nullable();
            $table->string('driver_document_type')->nullable();
            $table->string('driver_document_number')->nullable();
            $table->string('driver_residence_address')->nullable();
            $table->string('driver_residence_department_code')->nullable();
            $table->string('driver_residence_municipality_code')->nullable();
            $table->string('driver_residence_phone')->nullable();
            $table->string('primary_transfer_ambulance_plate')->nullable();
            $table->string('victim_transport_from_event_site')->nullable();
            $table->string('victim_transport_to_end')->nullable();
            $table->string('transport_service_type')->nullable();
            $table->string('victim_pickup_zone')->nullable();
            $table->string('doctor_first_last_name')->nullable();
            $table->string('doctor_second_last_name')->nullable();
            $table->string('doctor_first_name')->nullable();
            $table->string('doctor_second_name')->nullable();
            $table->string('doctor_registration_number')->nullable();
            $table->string('total_billed_medical_surgical')->nullable();
            $table->string('total_claimed_medical_surgical')->nullable();
            $table->string('total_billed_transport')->nullable();
            $table->string('total_claimed_transport')->nullable();
            $table->string('enabled_services_confirmation')->nullable();
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
