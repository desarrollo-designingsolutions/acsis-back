<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum VictimConditionEnum: string
{
    use AttributableEnum;

    #[Description('Conductor')]
    #[Value('1')]
    case VICTIM_CONDITION_001 = 'VICTIM_CONDITION_001';

    #[Description('Peatón')]
    #[Value('2')]
    case VICTIM_CONDITION_002 = 'VICTIM_CONDITION_002';

    #[Description('Ocupante')]
    #[Value('3')]
    case VICTIM_CONDITION_003 = 'VICTIM_CONDITION_003';

    #[Description('Ciclista')]
    #[Value('4')]
    case VICTIM_CONDITION_004 = 'VICTIM_CONDITION_004';
}
