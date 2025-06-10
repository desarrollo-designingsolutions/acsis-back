<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum VehicleTypeEnum: string
{
    use AttributableEnum;

    #[Description('Automóvil')]
    case VEHICLE_TYPE_001 = '1';

    #[Description('Bus')]
    case VEHICLE_TYPE_002 = '2';

    #[Description('Buseta')]
    case VEHICLE_TYPE_003 = '3';

    #[Description('Camión')]
    case VEHICLE_TYPE_004 = '4';

    #[Description('Camioneta')]
    case VEHICLE_TYPE_005 = '5';

    #[Description('Campero')]
    case VEHICLE_TYPE_006 = '6';

    #[Description('Microbus')]
    case VEHICLE_TYPE_007 = '7';

    #[Description('Tractocamión')]
    case VEHICLE_TYPE_008 = '8';

    #[Description('Motocicleta')]
    case VEHICLE_TYPE_009 = '10';

    #[Description('Motocarro')]
    case VEHICLE_TYPE_010 = '14';

    #[Description('Mototriciclo')]
    case VEHICLE_TYPE_011 = '17';

    #[Description('Cuatrimoto')]
    case VEHICLE_TYPE_012 = '19';

    #[Description('Moto Extrajera')]
    case VEHICLE_TYPE_013 = '20';

    #[Description('Vehículo Extranjero')]
    case VEHICLE_TYPE_014 = '21';

    #[Description('Volqueta')]
    case VEHICLE_TYPE_015 = '22';
}
