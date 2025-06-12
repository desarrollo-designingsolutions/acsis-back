<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum EventNatureEnum: string
{
    use AttributableEnum;

    #[Description('Accidente de tránsito')]
    case EVENT_NATURE_001 = '01';

    #[Description('Sismo')]
    case EVENT_NATURE_002 = '02';

    #[Description('Maremoto')]
    case EVENT_NATURE_003 = '03';

    #[Description('Erupción volcánica')]
    case EVENT_NATURE_004 = '04';

    #[Description('Deslizamiento de tierra')]
    case EVENT_NATURE_005 = '05';

    #[Description('Inundación')]
    case EVENT_NATURE_006 = '06';

    #[Description('Avalancha')]
    case EVENT_NATURE_007 = '07';

    #[Description('Incendio natural')]
    case EVENT_NATURE_008 = '08';

    #[Description('Explosión terrorista')]
    case EVENT_NATURE_009 = '09';

    #[Description('Incendio terrorista')]
    case EVENT_NATURE_010 = '10';

    #[Description('Combate')]
    case EVENT_NATURE_011 = '11';

    #[Description('Ataques a Municipios')]
    case EVENT_NATURE_012 = '12';

    #[Description('Masacre')]
    case EVENT_NATURE_013 = '13';

    #[Description('Desplazados')]
    case EVENT_NATURE_014 = '14';

    #[Description('Mina antipersonal')]
    case EVENT_NATURE_015 = '15';

    #[Description('Huracán')]
    case EVENT_NATURE_016 = '16';

    #[Description('Otro')]
    case EVENT_NATURE_017 = '17';

    #[Description('Rayo')]
    case EVENT_NATURE_018 = '25';

    #[Description('Vendaval')]
    case EVENT_NATURE_019 = '26';

    #[Description('Tornado')]
    case EVENT_NATURE_020 = '27';
}
