<?php

namespace App\Enums;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum GenderEnum: string
{
    use AttributableEnum;

    #[Description('Femenino')]
    #[Value('F')]
    case GENDER_001 = 'GENDER_001';

    #[Description('Masculino')]
    #[Value('M')]
    case GENDER_002 = 'GENDER_002';

    #[Description('Otro')]
    #[Value('0')]
    case GENDER_003 = 'GENDER_003';
}
