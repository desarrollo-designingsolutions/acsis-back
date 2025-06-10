<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum PickupZoneEnum: string
{
    use AttributableEnum;

    #[Description('Urbano')]
    case PICKUP_ZONE_001 = 'U';

    #[Description('Rural')]
    case PICKUP_ZONE_002 = 'R';
}
