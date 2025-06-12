<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum SurgicalComplexityEnum: string
{
    use AttributableEnum;

    #[Description('Alta')]
    case SURGICAL_COMPLEXITY_001 = '1';

    #[Description('Media')]
    case SURGICAL_COMPLEXITY_002 = '2';

    #[Description('Baja')]
    case SURGICAL_COMPLEXITY_003 = '3';
}
