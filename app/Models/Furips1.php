<?php

namespace App\Models;

use App\Enums\Furips1\RgoResponseEnum;
use App\Enums\Furips1\VictimConditionEnum;
use App\Enums\Furips1\EventNatureEnum;
use App\Enums\Furips1\EventZoneEnum;
use App\Enums\Furips1\YesNoEnum;
use App\Enums\Furips1\PickupZoneEnum;
use App\Enums\Furips1\ReferenceTypeEnum;
use App\Enums\Furips1\SurgicalComplexityEnum;
use App\Enums\Furips1\TransportServiceTypeEnum;
use App\Enums\Furips1\VehicleTypeEnum;
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
        'eventZone' => EventZoneEnum::class,
        'insurerCapExhaustionCharge' => YesNoEnum::class,
        'referenceType' => ReferenceTypeEnum::class,
        'rgoResponse' => RgoResponseEnum::class,
        'surgicalProcedureComplexity' => SurgicalComplexityEnum::class,
        'transportServiceType' => TransportServiceTypeEnum::class,
        'vehicleType' => VehicleTypeEnum::class,
        'victimCondition' => VictimConditionEnum::class,
        'victimPickupZone' => PickupZoneEnum::class,
        'enabledServicesConfirmation' => YesNoEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
