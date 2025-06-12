<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum YesNoEnum: string
{
    use AttributableEnum;

    #[Description('No')]
    case INSURANCE_LIMIT_CHARGE_001 = '0';

    #[Description('Sí')]
    case INSURANCE_LIMIT_CHARGE_002 = '1';
}
