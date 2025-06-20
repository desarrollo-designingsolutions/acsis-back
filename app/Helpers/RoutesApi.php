<?php

namespace App\Helpers;

class RoutesApi
{
    // esto es para las apis que no requieran auth
    public const ROUTES_API = [
        'routes/api.php',
    ];

    // esto es para las apis que si requieran auth
    public const ROUTES_AUTH_API = [
        'routes/cache.php',
        'routes/query.php',
        'routes/company.php',
        'routes/user.php',
        'routes/role.php',
        'routes/notification.php',
        'routes/entity.php',
        'routes/serviceVendor.php',
        'routes/file.php',
        'routes/invoice.php',
        'routes/service.php',
        'routes/glosa.php',
        'routes/invoicePayment.php',
        'routes/patient.php',
        'routes/dashboard.php',
        'routes/glosaAnswer.php',
        'routes/furips1.php',
        'routes/furips2.php',
        'routes/furtran.php',
    ];
}
