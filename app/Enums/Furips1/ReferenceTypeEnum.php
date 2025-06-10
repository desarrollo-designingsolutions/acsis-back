<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum ReferenceTypeEnum: string
{
    use AttributableEnum;

    #[Description('Remisión')]
    case REFERENCE_TYPE_001 = '1';

    #[Description('Orden de servicio')]
    case REFERENCE_TYPE_002 = '2';
}
