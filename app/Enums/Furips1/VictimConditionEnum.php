<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum VictimConditionEnum: string
{
    use AttributableEnum;

    #[Description('Conductor')]
    case VICTIM_CONDITION_001 = '1';

    #[Description('Peatón')]
    case VICTIM_CONDITION_002 = '2';

    #[Description('Ocupante')]
    case VICTIM_CONDITION_003 = '3';

    #[Description('Ciclista')]
    case VICTIM_CONDITION_004 = '4';
}
