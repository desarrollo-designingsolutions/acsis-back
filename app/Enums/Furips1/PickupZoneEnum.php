<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum PickupZoneEnum: string
{
    use AttributableEnum;

    #[Description('Urbano')]
    #[Value('U')]
    case PICKUP_ZONE_001 = 'PICKUP_ZONE_001';

    #[Description('Rural')]
    #[Value('R')]
    case PICKUP_ZONE_002 = 'PICKUP_ZONE_002';
}
