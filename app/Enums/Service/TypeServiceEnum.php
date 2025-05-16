<?php

namespace App\Enums\Service;

use App\Attributes\Description;
use App\Attributes\ElementJson;
use App\Attributes\Model;
use App\Traits\AttributableEnum;

enum TypeServiceEnum: string
{
    use AttributableEnum;

    #[Description('Consultas')]
    #[Model('App\\Models\\MedicalConsultation')]
    #[ElementJson('consultas')]
    case SERVICE_TYPE_001 = 'SERVICE_TYPE_001';

    #[Description('Procedimientos')]
    #[Model('App\\Models\\Procedure')]
    #[ElementJson('procedimientos')]
    case SERVICE_TYPE_002 = 'SERVICE_TYPE_002';

    #[Description('Urgencias')]
    case SERVICE_TYPE_003 = 'SERVICE_TYPE_003';

    #[Description('Hospitalización')]
    case SERVICE_TYPE_004 = 'SERVICE_TYPE_004';

    #[Description('Recien nacidos')]
    case SERVICE_TYPE_005 = 'SERVICE_TYPE_005';

    #[Description('Medicamentos')]
    case SERVICE_TYPE_006 = 'SERVICE_TYPE_006';

    #[Description('Otros servicios')]
    #[Model('App\\Models\\OtherService')]
    #[ElementJson('otrosServicios')]
    case SERVICE_TYPE_007 = 'SERVICE_TYPE_007';
}
