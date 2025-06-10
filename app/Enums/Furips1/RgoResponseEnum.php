<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum RgoResponseEnum: string
{
    use AttributableEnum;

    #[Description('Glosa u objeción total')]
    case RGO_RESPONSE_001 = '0';

    #[Description('Pago parcial')]
    case RGO_RESPONSE_002 = '1';

    #[Description('Glosa Transversal')]
    case RGO_RESPONSE_003 = '6';
}
