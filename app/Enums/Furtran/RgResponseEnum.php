<?php

namespace App\Enums\Furtran;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum RgResponseEnum: string
{
    use AttributableEnum;

    #[Description('Glosa u objeción total')]
    #[Value('0')]
    case RG_RESPONSE_001 = 'RG_RESPONSE_001';

    #[Description('Pago parcial')]
    #[Value('1')]
    case RG_RESPONSE_002 = 'RG_RESPONSE_002';

    #[Description('Glosa Transversal')]
    #[Value('6')]
    case RG_RESPONSE_003 = 'RG_RESPONSE_003';
}
