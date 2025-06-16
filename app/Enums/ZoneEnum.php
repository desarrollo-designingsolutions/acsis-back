<?php

namespace App\Enums;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum ZoneEnum: string
{
    use AttributableEnum;

    #[Description('Urbana')]
    #[Value('U')]
    case ZONE_001 = 'ZONE_001';

    #[Description('Rural')]
    #[Value('R')]
    case ZONE_002 = 'ZONE_002';
}
