<?php

namespace App\Enums\Service;

use App\Attributes\Description;
use App\Attributes\ElementJson;
use App\Attributes\Icon;
use App\Attributes\Color;
use App\Attributes\Model;
use App\Traits\AttributableEnum;

enum TypeServiceEnum: string
{
    use AttributableEnum;

    #[Description('Consultas')]
    #[Model('App\\Models\\MedicalConsultation')]
    #[ElementJson('consultas')]
    #[Icon('tabler-report-medical')]
    #[Color('bg-blue-500')]
    case SERVICE_TYPE_001 = 'SERVICE_TYPE_001';

    #[Description('Procedimientos')]
    #[Model('App\\Models\\Procedure')]
    #[ElementJson('procedimientos')]
    #[Icon('tabler-checkup-list')]
    #[Color('bg-green-500')]
    case SERVICE_TYPE_002 = 'SERVICE_TYPE_002';

    #[Description('Urgencias')]
    #[Model('App\\Models\\Urgency')]
    #[ElementJson('urgencias')]
    #[Icon('tabler-urgent')]
    #[Color('bg-red-500')]
    case SERVICE_TYPE_003 = 'SERVICE_TYPE_003';

    #[Description('Hospitalización')]
    #[Model('App\\Models\\Hospitalization')]
    #[ElementJson('hospitalizacion')]
    #[Icon('tabler-building-hospital')]
    #[Color('bg-purple-500')]
    case SERVICE_TYPE_004 = 'SERVICE_TYPE_004';

    #[Description('Recien nacidos')]
    #[Model('App\\Models\\NewlyBorn')]
    #[ElementJson('recienNacidos')]
    #[Icon('tabler-baby-carriage')]
    #[Color('bg-pink-500')]
    case SERVICE_TYPE_005 = 'SERVICE_TYPE_005';

    #[Description('Medicamentos')]
    #[Model('App\\Models\\Medicine')]
    #[ElementJson('medicamentos')]
    #[Icon('tabler-pill')]
    #[Color('bg-yellow-500')]
    case SERVICE_TYPE_006 = 'SERVICE_TYPE_006';

    #[Description('Otros servicios')]
    #[Model('App\\Models\\OtherService')]
    #[ElementJson('otrosServicios')]
    #[Icon('tabler-heart-cog')]
    #[Color('bg-teal-500')]
    case SERVICE_TYPE_007 = 'SERVICE_TYPE_007';
}
