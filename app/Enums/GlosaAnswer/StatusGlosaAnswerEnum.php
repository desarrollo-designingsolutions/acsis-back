<?php

namespace App\Enums\GlosaAnswer;

use App\Attributes\BackgroundColor;
use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum StatusGlosaAnswerEnum: string
{
    use AttributableEnum;

    #[Description('No Conciliado')]
    #[BackgroundColor('#FFD1DC')] // Rosa pastel
    case GLOSA_ANSWER_STATUS_001 = 'GLOSA_ANSWER_STATUS_001';

    #[Description('Conciliado')]
    #[BackgroundColor('#C8E6C9')] // Verde pastel
    case GLOSA_ANSWER_STATUS_002 = 'GLOSA_ANSWER_STATUS_002';
}
