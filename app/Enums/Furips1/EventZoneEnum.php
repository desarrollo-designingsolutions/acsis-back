<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum EventZoneEnum: string
{
    use AttributableEnum;

    #[Description('Urbana')]
    case EVENT_ZONE_001 = 'U';

    #[Description('Rural')]
    case EVENT_ZONE_002 = 'R';
}
