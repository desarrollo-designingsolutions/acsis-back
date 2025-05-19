<?php

namespace App\Enums\Service;

use App\Attributes\Description;
use App\Attributes\ElementJson;
use App\Attributes\Icon;
use App\Attributes\Model;
use App\Traits\AttributableEnum;

enum TypeServiceEnum: string
{
    use AttributableEnum;

    #[Description('Consultas')]
    #[Model('App\\Models\\MedicalConsultation')]
    #[ElementJson('consultas')]
    #[Icon('tabler-report-medical')]
    case SERVICE_TYPE_001 = 'SERVICE_TYPE_001';

    #[Description('Procedimientos')]
    #[Model('App\\Models\\Procedure')]
    #[ElementJson('procedimientos')]
    #[Icon('tabler-checkup-list')]
    case SERVICE_TYPE_002 = 'SERVICE_TYPE_002';

    #[Description('Urgencias')]
    #[Model('App\\Models\\Urgency')]
    #[ElementJson('urgencias')]
    #[Icon('tabler-urgent')]
    case SERVICE_TYPE_003 = 'SERVICE_TYPE_003';

    #[Description('Hospitalización')]
    #[Model('App\\Models\\Hospitalization')]
    #[ElementJson('hospitalizacion')]
    #[Icon('tabler-building-hospital')]
    case SERVICE_TYPE_004 = 'SERVICE_TYPE_004';

    #[Description('Recien nacidos')]
    #[Model('App\\Models\\NewlyBorn')]
    #[ElementJson('recienNacidos')]
    #[Icon('tabler-baby-carriage')]
    case SERVICE_TYPE_005 = 'SERVICE_TYPE_005';

    #[Description('Medicamentos')]
    #[Model('App\\Models\\Medicine')]
    #[ElementJson('medicamentos')]
    #[Icon('tabler-pill')]
    case SERVICE_TYPE_006 = 'SERVICE_TYPE_006';

    #[Description('Otros servicios')]
    #[Model('App\\Models\\OtherService')]
    #[ElementJson('otrosServicios')]
    #[Icon('tabler-heart-cog')]
    case SERVICE_TYPE_007 = 'SERVICE_TYPE_007';
}
