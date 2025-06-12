<?php

namespace App\Enums\Furips2;

use App\Attributes\Description;
use App\Attributes\Value;
use App\Traits\AttributableEnum;

enum ServiceTypeEnum: string
{
    use AttributableEnum;

    #[Description('Medicamentos')]
    #[Value('1')]
    case SERVICE_TYPE_001 = 'SERVICE_TYPE_001';

    #[Description('Procedimientos')]
    #[Value('2')]
    case SERVICE_TYPE_002 = 'SERVICE_TYPE_002';

    #[Description('Transporte Primario')]
    #[Value('3')]
    case SERVICE_TYPE_003 = 'SERVICE_TYPE_003';

    #[Description('Transporte Secundario')]
    #[Value('4')]
    case SERVICE_TYPE_004 = 'SERVICE_TYPE_004';

    #[Description('Insumos')]
    #[Value('5')]
    case SERVICE_TYPE_005 = 'SERVICE_TYPE_005';

    #[Description('Dispositivos Médicos')]
    #[Value('6')]
    case SERVICE_TYPE_006 = 'SERVICE_TYPE_006';

    #[Description('Material de Osteosíntesis')]
    #[Value('7')]
    case SERVICE_TYPE_007 = 'SERVICE_TYPE_007';

    #[Description('Procedimiento no incluido en el manual tarifario')]
    #[Value('8')]
    case SERVICE_TYPE_008 = 'SERVICE_TYPE_008';
}
