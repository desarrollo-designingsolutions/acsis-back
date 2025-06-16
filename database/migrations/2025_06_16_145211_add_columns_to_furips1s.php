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

            $table->string('medicalAdmissionDate')->nullable();
            $table->string('medicalAdmissionTime')->nullable();
            $table->string('medicalDischargeDate')->nullable();
            $table->string('medicalDischargeTime')->nullable();

            $table->foreignUuid('primaryAdmissionDiagnosisCode_id')->nullable()->constrained('cie10s');
            $table->foreignUuid('associatedAdmissionDiagnosisCode1_id')->nullable()->constrained('cie10s');
            $table->foreignUuid('associatedAdmissionDiagnosisCode2_id')->nullable()->constrained('cie10s');
            $table->foreignUuid('primaryDischargeDiagnosisCode_id')->nullable()->constrained('cie10s');
            $table->foreignUuid('associatedDischargeDiagnosisCode1_id')->nullable()->constrained('cie10s');
            $table->foreignUuid('associatedDischargeDiagnosisCode2_id')->nullable()->constrained('cie10s');
            $table->string('authorityIntervention')->nullable();
            $table->string('policyExcessCharge')->nullable();
            $table->string('referralRecipientCharge')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('furips1s', function (Blueprint $table) {
            $table->dropColumn('medicalAdmissionDate');
            $table->dropColumn('medicalAdmissionTime');
            $table->dropColumn('medicalDischargeDate');
            $table->dropColumn('medicalDischargeTime');

            $table->dropConstrainedForeignId('primaryAdmissionDiagnosisCode_id');
            $table->dropConstrainedForeignId('associatedAdmissionDiagnosisCode1_id');
            $table->dropConstrainedForeignId('associatedAdmissionDiagnosisCode2_id');
            $table->dropConstrainedForeignId('primaryDischargeDiagnosisCode_id');
            $table->dropConstrainedForeignId('associatedDischargeDiagnosisCode1_id');
            $table->dropConstrainedForeignId('associatedDischargeDiagnosisCode2_id');

            $table->dropColumn('authorityIntervention')->nullable();
            $table->dropColumn('policyExcessCharge')->nullable();
            $table->dropColumn('referralRecipientCharge')->nullable();
        });
    }
};
