<?php

namespace App\Enums\Invoice;

use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum StatusInvoiceEnum: string
{
    use AttributableEnum;

    #[Description('Cancelado')]
    case INVOICE_STATUS_001 = 'INVOICE_STATUS_001';

    #[Description('Radicado')]
    case INVOICE_STATUS_002 = 'INVOICE_STATUS_002';

    #[Description('Pagado')]
    case INVOICE_STATUS_003 = 'INVOICE_STATUS_003';

    #[Description('Glosado')]
    case INVOICE_STATUS_004 = 'INVOICE_STATUS_004';

    #[Description('Glosado con respuesta')]
    case INVOICE_STATUS_005 = 'INVOICE_STATUS_005';

    #[Description('Pagado parcial')]
    case INVOICE_STATUS_006 = 'INVOICE_STATUS_006';

    #[Description('Devolucion')]
    case INVOICE_STATUS_007 = 'INVOICE_STATUS_007';

    #[Description('Pendiente')]
    case INVOICE_STATUS_008 = 'INVOICE_STATUS_008';
}
