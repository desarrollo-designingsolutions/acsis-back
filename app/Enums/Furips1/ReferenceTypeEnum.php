<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum ReferenceTypeEnum: string
{
    use AttributableEnum;

    #[Description('Remisión')]
    #[Value('1')]
    case REFERENCE_TYPE_001 = 'REFERENCE_TYPE_001';

    #[Description('Orden de servicio')]
    #[Value('2')]
    case REFERENCE_TYPE_002 = 'REFERENCE_TYPE_002';
}
