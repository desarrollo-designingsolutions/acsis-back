<?php

namespace App\Enums\Furips1;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum TransportServiceTypeEnum: string
{
    use AttributableEnum;

    #[Description('Transporte básico')]
    case TRANSPORT_SERVICE_TYPE_001 = '1';

    #[Description('Transporte medicalizado')]
    case TRANSPORT_SERVICE_TYPE_002 = '2';
}
