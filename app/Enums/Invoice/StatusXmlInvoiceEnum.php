<?php

namespace App\Enums\Invoice;

use App\Attributes\BackgroundColor;
use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum StatusXmlInvoiceEnum: string
{
    use AttributableEnum;

    #[Description('Pendiente')]
    #[BackgroundColor('info')]
    case INVOICE_STATUS_XML_001 = 'INVOICE_STATUS_XML_001';

    #[Description('Error XML')]
    #[BackgroundColor('error')]
    case INVOICE_STATUS_XML_002 = 'INVOICE_STATUS_XML_002';

    #[Description('Validado')]
    #[BackgroundColor('success')]
    case INVOICE_STATUS_XML_003 = 'INVOICE_STATUS_XML_003';
}
