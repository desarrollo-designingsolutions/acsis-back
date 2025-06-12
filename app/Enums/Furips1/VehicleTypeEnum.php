<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum VehicleTypeEnum: string
{
    use AttributableEnum;

    #[Description('Automóvil')]
    #[Value('1')]
    case VEHICLE_TYPE_001 = 'VEHICLE_TYPE_001';

    #[Description('Bus')]
    #[Value('2')]
    case VEHICLE_TYPE_002 = 'VEHICLE_TYPE_002';

    #[Description('Buseta')]
    #[Value('3')]
    case VEHICLE_TYPE_003 = 'VEHICLE_TYPE_003';

    #[Description('Camión')]
    #[Value('4')]
    case VEHICLE_TYPE_004 = 'VEHICLE_TYPE_004';

    #[Description('Camioneta')]
    #[Value('5')]
    case VEHICLE_TYPE_005 = 'VEHICLE_TYPE_005';

    #[Description('Campero')]
    #[Value('6')]
    case VEHICLE_TYPE_006 = 'VEHICLE_TYPE_006';

    #[Description('Microbus')]
    #[Value('7')]
    case VEHICLE_TYPE_007 = 'VEHICLE_TYPE_007';

    #[Description('Tractocamión')]
    #[Value('8')]
    case VEHICLE_TYPE_008 = 'VEHICLE_TYPE_008';

    #[Description('Motocicleta')]
    #[Value('10')]
    case VEHICLE_TYPE_009 = 'VEHICLE_TYPE_009';

    #[Description('Motocarro')]
    #[Value('14')]
    case VEHICLE_TYPE_010 = 'VEHICLE_TYPE_010';

    #[Description('Mototriciclo')]
    #[Value('17')]
    case VEHICLE_TYPE_011 = 'VEHICLE_TYPE_011';

    #[Description('Cuatrimoto')]
    #[Value('19')]
    case VEHICLE_TYPE_012 = 'VEHICLE_TYPE_012';

    #[Description('Moto Extrajera')]
    #[Value('20')]
    case VEHICLE_TYPE_013 = 'VEHICLE_TYPE_013';

    #[Description('Vehículo Extranjero')]
    #[Value('21')]
    case VEHICLE_TYPE_014 = 'VEHICLE_TYPE_014';

    #[Description('Volqueta')]
    #[Value('22')]
    case VEHICLE_TYPE_015 = 'VEHICLE_TYPE_015';
}
