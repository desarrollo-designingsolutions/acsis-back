<?php

namespace App\Enums\Invoice;

use App\Attributes\BackgroundColor;
use App\Attributes\Description;
use App\Traits\AttributableEnum;

enum StatusInvoiceEnum: string
{
    use AttributableEnum;

    #[Description('Cancelado')]
    #[BackgroundColor('#FFD1DC')] // Rosa pastel
    case INVOICE_STATUS_001 = 'INVOICE_STATUS_001';

    #[Description('Radicado')]
    #[BackgroundColor('#B3E5FC')] // Azul claro pastel
    case INVOICE_STATUS_002 = 'INVOICE_STATUS_002';

    #[Description('Pagado')]
    #[BackgroundColor('#C8E6C9')] // Verde pastel
    case INVOICE_STATUS_003 = 'INVOICE_STATUS_003';

    #[Description('Glosado')]
    #[BackgroundColor('#FFF9C4')] // Amarillo pastel
    case INVOICE_STATUS_004 = 'INVOICE_STATUS_004';

    #[Description('Glosado con respuesta')]
    #[BackgroundColor('#FFE0B2')] // Naranja pastel
    case INVOICE_STATUS_005 = 'INVOICE_STATUS_005';

    #[Description('Pagado parcial')]
    #[BackgroundColor('#E1BEE7')] // Lila pastel
    case INVOICE_STATUS_006 = 'INVOICE_STATUS_006';

    #[Description('Devolucion')]
    #[BackgroundColor('#B2EBF2')] // Turquesa pastel
    case INVOICE_STATUS_007 = 'INVOICE_STATUS_007';

    #[Description('Pendiente')]
    #[BackgroundColor('#D1C4E9')] // Lavanda pastel
    case INVOICE_STATUS_008 = 'INVOICE_STATUS_008';
}
