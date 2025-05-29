<?php

namespace App\Enums\Invoice;

use App\Attributes\Description;
use App\Attributes\Model;
use App\Traits\AttributableEnum;

enum TypeInvoiceEnum: string
{
    use AttributableEnum;

    #[Description('Evento')]
    case INVOICE_TYPE_001 = 'INVOICE_TYPE_001';

    #[Description('Soat')]
    #[Model('App\\Models\\InvoiceSoat')]
    case INVOICE_TYPE_002 = 'INVOICE_TYPE_002';

    #[Description('Arl')]
    case INVOICE_TYPE_003 = 'INVOICE_TYPE_003';
}
