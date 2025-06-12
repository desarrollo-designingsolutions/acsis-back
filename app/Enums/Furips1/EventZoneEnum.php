<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum EventZoneEnum: string
{
    use AttributableEnum;

    #[Description('Urbana')]
    #[Value('U')]
    case EVENT_ZONE_001 = 'EVENT_ZONE_001';

    #[Description('Rural')]
    #[Value('R')]
    case EVENT_ZONE_002 = 'EVENT_ZONE_002';
}
