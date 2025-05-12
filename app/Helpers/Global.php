<?php

use App\Events\ChangeInvoiceAuditData;
use App\Models\Invoice;
use App\Models\Service;
use App\Services\CacheService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

function logMessage($message)
{
    Log::info($message);
}

function paginatePerzonalized($data)
{
    $average = collect($data);

    $tamanoPagina = request('perPage', 20); // El número de elementos por página
    $pagina = request('page', 1); // Obtén el número de página de la solicitud

    // Divide la colección en segmentos de acuerdo al tamaño de la página
    $segmentos = array_chunk($average->toArray(), $tamanoPagina);

    // Obtén el segmento correspondiente a la página actual
    $segmentoActual = array_slice($segmentos, $pagina - 1, 1);

    // Convierte el segmento de nuevo a una colección
    $paginado = collect([]);
    if (isset($segmentoActual[0])) {
        $paginado = collect($segmentoActual[0]);
    }

    // Crea una instancia de la clase LengthAwarePaginator
    return $paginate = new \Illuminate\Pagination\LengthAwarePaginator(
        $paginado,
        count($average),
        $tamanoPagina,
        $pagina,
        ['path' => url()->current()]
    );
}

function clearCacheLaravel()
{
    // Limpia la caché de permisos
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
}

function generatePastelColor($opacity = 1.0)
{
    $red = mt_rand(100, 255);
    $green = mt_rand(100, 255);
    $blue = mt_rand(100, 255);

    // Asegúrate de que la opacidad esté en el rango adecuado (0 a 1)
    $opacity = max(0, min(1, $opacity));

    return sprintf('rgba(%d, %d, %d, %.2f)', $red, $green, $blue, $opacity);
}

function truncate_text($text, $maxLength = 15)
{
    if (strlen($text) > $maxLength) {
        return substr($text, 0, $maxLength) . '...';
    }

    return $text;
}

function formatNumber($number, $currency_symbol = '$ ', $decimal = 2)
{
    // Asegúrate de que el número es un número flotante
    $formattedNumber = number_format((float) $number, $decimal, ',', '.');

    return $currency_symbol . $formattedNumber;
}

function formattedElement($element)
{
    // Convertir el valor en función de su contenido
    switch ($element) {
        case 'null':
            $element = null;
            break;
        case 'undefined':
            $element = null;
            break;
        case 'true':
            $element = true;
            break;
        case 'false':
            $element = false;
            break;
        default:
            // No es necesario hacer nada si el valor no coincide
            break;
    }

    return $element;
}

function getValueSelectInfinite($field, $value = 'value')
{
    return isset($field) && is_array($field) ? $field[$value] : $field;
}

function convertNullToEmptyString(array $data): array
{
    return array_map(function ($item) {
        return $item === null ? '' : $item;
    }, $data);
}


function changeServiceData($service_id)
{
    $service = Service::with(["invoice"])->find($service_id);

    // Calcular el valor total de las glosas para el servicio
    $value_glosa = $service->glosas->sum('glosa_value');

    // Asegurarse de que value_glosa no exceda total_value
    if ($value_glosa > $service->total_value) {
        $value_glosa = $service->total_value;
    }

    // Calcular el valor aprobado
    $value_approved = $service->total_value - $value_glosa;

    // Actualizar la BD MySQL para el servicio
    $service->update([
        'value_glosa' => $value_glosa,
        'value_approved' => $value_approved,
    ]);

    // Obtener todos los servicios de la factura
    $services = $service->invoice->services;

    // Sumar los valores de value_glosa y value_approved de todos los servicios
    $total_value_glosa = $services->sum('value_glosa');
    $total_value_approved = $services->sum('value_approved');

    // Actualizar el campo value_glosa en la factura
    $service->invoice->update([
        'value_glosa' => $total_value_glosa,
    ]);

    Invoice::updateTotalFromServices($service->invoice_id);
}
