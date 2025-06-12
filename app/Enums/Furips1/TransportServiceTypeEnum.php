<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum TransportServiceTypeEnum: string
{
    use AttributableEnum;

    #[Description('Transporte básico')]
    #[Value('1')]
    case TRANSPORT_SERVICE_TYPE_001 = 'TRANSPORT_SERVICE_TYPE_001';

    #[Description('Transporte medicalizado')]
    #[Value('2')]
    case TRANSPORT_SERVICE_TYPE_002 = 'TRANSPORT_SERVICE_TYPE_002';
}
