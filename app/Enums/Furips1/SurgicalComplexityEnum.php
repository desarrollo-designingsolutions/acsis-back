<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum SurgicalComplexityEnum: string
{
    use AttributableEnum;

    #[Description('Alta')]
    #[Value('1')]
    case SURGICAL_COMPLEXITY_001 = 'SURGICAL_COMPLEXITY_001';

    #[Description('Media')]
    #[Value('2')]
    case SURGICAL_COMPLEXITY_002 = 'SURGICAL_COMPLEXITY_002';

    #[Description('Baja')]
    #[Value('3')]
    case SURGICAL_COMPLEXITY_003 = 'SURGICAL_COMPLEXITY_003';
}
