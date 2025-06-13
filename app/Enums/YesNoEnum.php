<?php

namespace App\Enums;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum YesNoEnum: string
{
    use AttributableEnum;

    #[Description('No')]
    #[Value('0')]
    case YES_NO_001 = 'YES_NO_001';

    #[Description('Sí')]
    #[Value('1')]
    case YES_NO_002 = 'YES_NO_002';
}
