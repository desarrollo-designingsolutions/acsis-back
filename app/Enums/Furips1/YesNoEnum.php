<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum YesNoEnum: string
{
    use AttributableEnum;

    #[Description('No')]
    case YES_NO_001 = '0';

    #[Description('Sí')]
    case YES_NO_002 = '1';
}
