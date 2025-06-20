<?php

namespace App\Enums\Furtran;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum EventTypeEnum: string
{
    use AttributableEnum;

    #[Description('Accidente de tránsito')]
    #[Value('1')]
    case EVENT_TYPE_001 = 'EVENT_TYPE_001';

    #[Description('Evento Catastrófico')]
    #[Value('2')]
    case EVENT_TYPE_002 = 'EVENT_TYPE_002';

    #[Description('Evento Terrorista')]
    #[Value('3')]
    case EVENT_TYPE_003 = 'EVENT_TYPE_003';
}
