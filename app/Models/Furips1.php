<?php

namespace App\Models;

use App\Enums\Furips1\EventNatureEnum;
use App\Enums\Furips1\ReferenceTypeEnum;
use App\Enums\Furips1\RgoResponseEnum;
use App\Enums\Furips1\SurgicalComplexityEnum;
use App\Enums\Furips1\TransportServiceTypeEnum;
use App\Enums\Furips1\VehicleTypeEnum;
use App\Enums\Furips1\VictimConditionEnum;
use App\Enums\YesNoEnum;
use App\Enums\ZoneEnum;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Furips1 extends Model
{
    use Cacheable, HasFactory, HasUuids;

    protected $casts = [
        'eventNature' => EventNatureEnum::class,
        'eventZone' => ZoneEnum::class,
        'insurerCapExhaustionCharge' => YesNoEnum::class,
        'referenceType' => ReferenceTypeEnum::class,
        'rgoResponse' => RgoResponseEnum::class,
        'surgicalProcedureComplexity' => SurgicalComplexityEnum::class,
        'transportServiceType' => TransportServiceTypeEnum::class,
        'vehicleType' => VehicleTypeEnum::class,
        'victimCondition' => VictimConditionEnum::class,
        'victimPickupZone' => ZoneEnum::class,
        'enabledServicesConfirmation' => YesNoEnum::class,
        'uciServiceProvided' => YesNoEnum::class,
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function eventDepartmentCode(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'eventDepartmentCode_id', 'id');
    }

    public function ownerResidenceDepartmentCode(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'ownerResidenceDepartmentCode_id', 'id');
    }

    public function driverResidenceDepartmentCode(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'driverResidenceDepartmentCode_id', 'id');
    }

    public function eventMunicipalityCode(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'eventMunicipalityCode_id', 'id');
    }

    public function ownerResidenceMunicipalityCode(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'ownerResidenceMunicipalityCode_id', 'id');
    }

    public function driverResidenceMunicipalityCode(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'driverResidenceMunicipalityCode_id', 'id');
    }

    public function receivingHealthProviderCode(): BelongsTo
    {
        return $this->belongsTo(IpsCodHabilitacion::class, 'receivingHealthProviderCode_id', 'id');
    }

    public function referringHealthProviderCode(): BelongsTo
    {
        return $this->belongsTo(IpsCodHabilitacion::class, 'referringHealthProviderCode_id', 'id');
    }

    public function ownerDocumentType(): BelongsTo
    {
        return $this->belongsTo(TipoIdPisis::class, 'ownerDocumentType_id', 'id');
    }

    public function driverDocumentType(): BelongsTo
    {
        return $this->belongsTo(TipoIdPisis::class, 'driverDocumentType_id', 'id');
    }

    public function primaryAdmissionDiagnosisCode(): BelongsTo
    {
        return $this->belongsTo(Cie10::class, 'primaryAdmissionDiagnosisCode_id', 'id');
    }

    public function associatedAdmissionDiagnosisCode1(): BelongsTo
    {
        return $this->belongsTo(Cie10::class, 'associatedAdmissionDiagnosisCode1_id', 'id');
    }

    public function associatedAdmissionDiagnosisCode2(): BelongsTo
    {
        return $this->belongsTo(Cie10::class, 'associatedAdmissionDiagnosisCode2_id', 'id');
    }

    public function primaryDischargeDiagnosisCode(): BelongsTo
    {
        return $this->belongsTo(Cie10::class, 'primaryDischargeDiagnosisCode_id', 'id');
    }

    public function associatedDischargeDiagnosisCode1(): BelongsTo
    {
        return $this->belongsTo(Cie10::class, 'associatedDischargeDiagnosisCode1_id', 'id');
    }

    public function associatedDischargeDiagnosisCode2(): BelongsTo
    {
        return $this->belongsTo(Cie10::class, 'associatedDischargeDiagnosisCode2_id', 'id');
    }
    
    public function doctorIdType(): BelongsTo
    {
        return $this->belongsTo(TipoIdPisis::class, 'doctorIdType_id', 'id');
    }
    
        public function mainHospitalizationCupsCode(): BelongsTo
        {
            return $this->belongsTo(CupsRips::class, 'mainHospitalizationCupsCode_id', 'id');
        }
    
        public function mainSurgicalProcedureCupsCode(): BelongsTo
        {
            return $this->belongsTo(CupsRips::class, 'mainSurgicalProcedureCupsCode_id', 'id');
        }
    
        public function secondarySurgicalProcedureCupsCode(): BelongsTo
        {
            return $this->belongsTo(CupsRips::class, 'secondarySurgicalProcedureCupsCode_id', 'id');
        }
}
