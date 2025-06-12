<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum RgoResponseEnum: string
{
    use AttributableEnum;

    #[Description('Glosa u objeción total')]
    #[Value('0')]
    case RGO_RESPONSE_001 = 'RGO_RESPONSE_001';

    #[Description('Pago parcial')]
    #[Value('1')]
    case RGO_RESPONSE_002 = 'RGO_RESPONSE_002';

    #[Description('Glosa Transversal')]
    #[Value('6')]
    case RGO_RESPONSE_003 = 'RGO_RESPONSE_003';
}
