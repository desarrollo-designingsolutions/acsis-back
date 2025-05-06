<?php

namespace App\Enums\Invoice;

use App\Attributes\BackgroundColor;
use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum TypeInvoiceEnum: string
{
    use AttributableEnum;

    // OTROS
    #[Description('Evento')]
    case INVOICE_TYPE_001 = 'INVOICE_TYPE_001';

    #[Description('Soat')]
    case INVOICE_TYPE_002 = 'INVOICE_TYPE_002';

}
