<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum EventNatureEnum: string
{
    use AttributableEnum;

    #[Description('Accidente de tránsito')]
    #[Value('01')]
    case EVENT_NATURE_001 = 'EVENT_NATURE_001';

    #[Description('Sismo')]
    #[Value('02')]
    case EVENT_NATURE_002 = 'EVENT_NATURE_002';

    #[Description('Maremoto')]
    #[Value('03')]
    case EVENT_NATURE_003 = 'EVENT_NATURE_003';

    #[Description('Erupción volcánica')]
    #[Value('04')]
    case EVENT_NATURE_004 = 'EVENT_NATURE_004';

    #[Description('Deslizamiento de tierra')]
    #[Value('05')]
    case EVENT_NATURE_005 = 'EVENT_NATURE_005';

    #[Description('Inundación')]
    #[Value('06')]
    case EVENT_NATURE_006 = 'EVENT_NATURE_006';

    #[Description('Avalancha')]
    #[Value('07')]
    case EVENT_NATURE_007 = 'EVENT_NATURE_007';

    #[Description('Incendio natural')]
    #[Value('08')]
    case EVENT_NATURE_008 = 'EVENT_NATURE_008';

    #[Description('Explosión terrorista')]
    #[Value('09')]
    case EVENT_NATURE_009 = 'EVENT_NATURE_009';

    #[Description('Incendio terrorista')]
    #[Value('10')]
    case EVENT_NATURE_010 = 'EVENT_NATURE_010';

    #[Description('Combate')]
    #[Value('11')]
    case EVENT_NATURE_011 = 'EVENT_NATURE_011';

    #[Description('Ataques a Municipios')]
    #[Value('12')]
    case EVENT_NATURE_012 = 'EVENT_NATURE_012';

    #[Description('Masacre')]
    #[Value('13')]
    case EVENT_NATURE_013 = 'EVENT_NATURE_013';

    #[Description('Desplazados')]
    #[Value('14')]
    case EVENT_NATURE_014 = 'EVENT_NATURE_014';

    #[Description('Mina antipersonal')]
    #[Value('15')]
    case EVENT_NATURE_015 = 'EVENT_NATURE_015';

    #[Description('Huracán')]
    #[Value('16')]
    case EVENT_NATURE_016 = 'EVENT_NATURE_016';

    #[Description('Otro')]
    #[Value('17')]
    case EVENT_NATURE_017 = 'EVENT_NATURE_017';

    #[Description('Rayo')]
    #[Value('25')]
    case EVENT_NATURE_018 = 'EVENT_NATURE_018';

    #[Description('Vendaval')]
    #[Value('26')]
    case EVENT_NATURE_019 = 'EVENT_NATURE_019';

    #[Description('Tornado')]
    #[Value('27')]
    case EVENT_NATURE_020 = 'EVENT_NATURE_020';
}
