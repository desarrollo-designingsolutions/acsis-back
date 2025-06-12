<?php

namespace App\Enums\Furips2;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum ServiceTypeEnum: string
{
    use AttributableEnum;

    #[Description('Medicamentos')]
    case SERVICE_TYPE_001 = '1';

    #[Description('Procedimientos')]
    case SERVICE_TYPE_002 = '2';

    #[Description('Transporte Primario')]
    case SERVICE_TYPE_003 = '3';

    #[Description('Transporte Secundario')]
    case SERVICE_TYPE_004 = '4';

    #[Description('Insumos')]
    case SERVICE_TYPE_005 = '5';

    #[Description('Dispositivos Médicos')]
    case SERVICE_TYPE_006 = '6';

    #[Description('Material de Osteosíntesis')]
    case SERVICE_TYPE_007 = '7';

    #[Description('Procedimiento no incluido en el manual tarifario')]
    case SERVICE_TYPE_008 = '8';
}
