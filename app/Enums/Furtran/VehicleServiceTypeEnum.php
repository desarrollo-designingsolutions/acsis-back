<?php

namespace App\Enums\Furtran;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum VehicleServiceTypeEnum: string
{
    use AttributableEnum;

    #[Description('Ambulancia básica')]
    #[Value('1')]
    case VEHICLE_SERVICE_TYPE_001 = 'VEHICLE_SERVICE_TYPE_001';

    #[Description('Ambulancia medicalizada')]
    #[Value('2')]
    case VEHICLE_SERVICE_TYPE_002 = 'VEHICLE_SERVICE_TYPE_002';

    #[Description('Particular')]
    #[Value('3')]
    case VEHICLE_SERVICE_TYPE_003 = 'VEHICLE_SERVICE_TYPE_003';

    #[Description('Público')]
    #[Value('4')]
    case VEHICLE_SERVICE_TYPE_004 = 'VEHICLE_SERVICE_TYPE_004';

    #[Description('Oficial')]
    #[Value('5')]
    case VEHICLE_SERVICE_TYPE_005 = 'VEHICLE_SERVICE_TYPE_005';

    #[Description('De emergencia')]
    #[Value('6')]
    case VEHICLE_SERVICE_TYPE_006 = 'VEHICLE_SERVICE_TYPE_006';

    #[Description('Diplomático o consular')]
    #[Value('7')]
    case VEHICLE_SERVICE_TYPE_007 = 'VEHICLE_SERVICE_TYPE_007';

    #[Description('Transporte masivo')]
    #[Value('8')]
    case VEHICLE_SERVICE_TYPE_008 = 'VEHICLE_SERVICE_TYPE_008';

    #[Description('Escolar')]
    #[Value('9')]
    case VEHICLE_SERVICE_TYPE_009 = 'VEHICLE_SERVICE_TYPE_009';
}
