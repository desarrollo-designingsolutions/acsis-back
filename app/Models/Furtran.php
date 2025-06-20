<?php

namespace App\Models;

use App\Enums\Furtran\EventTypeEnum;
use App\Enums\Furtran\RgResponseEnum;
use App\Enums\Furtran\VehicleServiceTypeEnum;
use App\Enums\Furips1\VehicleTypeEnum;
use App\Enums\Furips1\VictimConditionEnum;
use App\Enums\GenderEnum;
use App\Enums\YesNoEnum;
use App\Enums\ZoneEnum;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Furtran extends Model
{
    use Cacheable, HasFactory, HasUuids;

    protected $casts = [
        'rgResponse' => RgResponseEnum::class,
        'pickupZone' => ZoneEnum::class,
        'vehicleServiceType' => VehicleServiceTypeEnum::class,
        'eventType' => EventTypeEnum::class,
        'victimGender' => GenderEnum::class,
        'victimCondition' => VictimConditionEnum::class,
        'involvedVehicleType' => VehicleTypeEnum::class,
        'serviceEnabledIndication' => YesNoEnum::class,
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function claimantIdType(): BelongsTo
    {
        return $this->belongsTo(TipoIdPisis::class, 'claimantIdType_id', 'id');
    }

    public function claimantDepartmentCode(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'claimantDepartmentCode_id', 'id');
    }

    public function pickupDepartmentCode(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'pickupDepartmentCode_id', 'id');
    }

    public function transferPickupDepartmentCode(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'transferPickupDepartmentCode_id', 'id');
    }

    public function claimantMunicipalityCode(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'claimantMunicipalityCode_id', 'id');
    }

    public function pickupMunicipalityCode(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'pickupMunicipalityCode_id', 'id');
    }

    public function transferPickupMunicipalityCode(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'transferPickupMunicipalityCode_id', 'id');
    }

    public function ipsReceptionHabilitationCode(): BelongsTo
    {
        return $this->belongsTo(IpsCodHabilitacion::class, 'ipsReceptionHabilitationCode_id', 'id');
    }
}
