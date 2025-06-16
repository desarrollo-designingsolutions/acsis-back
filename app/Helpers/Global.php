<?php

use App\Enums\Service\TypeServiceEnum;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        return substr($text, 0, $maxLength).'...';
    }

    return $text;
}

function formatNumber($number, $currency_symbol = '$ ', $decimal = 2)
{
    // Asegúrate de que el número es un número flotante
    $formattedNumber = number_format((float) $number, $decimal, ',', '.');

    return $currency_symbol.$formattedNumber;
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
    $service = Service::with(['invoice'])->find($service_id);

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

function updateInvoiceServicesJson(string $invoice_id, TypeServiceEnum $serviceType, array $serviceData = [], string $action = 'add', ?int $consecutivo = null)
{
    // Load invoice to get path_json and invoice_number
    $invoice = Invoice::select(['id', 'path_json', 'invoice_number', 'company_id'])->find($invoice_id);

    // Define file path
    $nameFile = $invoice->id.'.json';
    $path = "companies/company_{$invoice->company_id}/invoices/invoice_{$invoice->id}/{$nameFile}";
    $disk = Constants::DISK_FILES;

    // Initialize JSON data
    $jsonData = [];

    // Check if JSON file exists
    if (Storage::disk($disk)->exists($path)) {
        // Read existing JSON
        $jsonData = json_decode(Storage::disk($disk)->get($path), true);
    } else {
        // Initialize with minimal structure if JSON doesn't exist
        $jsonData = [
            'numDocumentoIdObligado' => '',
            'numFactura' => $invoice->invoice_number,
            'tipoNota' => '',
            'numNota' => '',
            'usuarios' => [
                [
                    'consecutivo' => 1,
                    'servicios' => [],
                ],
            ],
        ];
    }

    // Ensure servicios exists
    $jsonData['usuarios'][0]['servicios'] = $jsonData['usuarios'][0]['servicios'] ?? [];
    $jsonData['usuarios'][0]['servicios'][$serviceType->elementJson()] = $jsonData['usuarios'][0]['servicios'][$serviceType->elementJson()] ?? [];

    // Handle action
    switch ($action) {
        case 'add':
            // Add new service
            $jsonData['usuarios'][0]['servicios'][$serviceType->elementJson()][] = $serviceData;
            break;

        case 'edit':
            // Find and update service
            if ($consecutivo === null) {
                throw new \Exception('Service consecutivo is required for edit action');
            }
            foreach ($jsonData['usuarios'][0]['servicios'][$serviceType->elementJson()] as &$service) {
                if ($service['consecutivo'] === $consecutivo) {
                    $service = array_merge($service, $serviceData);
                    break;
                }
            }
            break;

        case 'delete':
            // Find and remove service
            if ($consecutivo === null) {
                throw new \Exception('Service consecutivo is required for delete action');
            }
            $jsonData['usuarios'][0]['servicios'][$serviceType->elementJson()] = array_filter(
                $jsonData['usuarios'][0]['servicios'][$serviceType->elementJson()],
                fn ($service) => $service['consecutivo'] !== $consecutivo
            );
            // Reindex consecutivos in JSON
            $newServices = [];
            $newConsecutivo = 1;
            foreach ($jsonData['usuarios'][0]['servicios'][$serviceType->elementJson()] as $service) {
                $service['consecutivo'] = $newConsecutivo++;
                $newServices[] = $service;
            }
            $jsonData['usuarios'][0]['servicios'][$serviceType->elementJson()] = $newServices;
            break;

        default:
            throw new \Exception('Invalid action specified');
    }

    // Store updated JSON
    Storage::disk($disk)->put($path, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // Update path_json in the invoice if it changed
    if ($invoice->path_json !== $path) {
        Invoice::update($invoice->id, ['path_json' => $path]);
    }

    return $jsonData;
}

function getNextConsecutivo(string $invoice_id, TypeServiceEnum $typeService)
{
    // Check JSON first
    $invoice = Invoice::select(['id', 'path_json', 'invoice_number', 'company_id'])->find($invoice_id);
    $path = "companies/company_{$invoice->company_id}/invoices/invoice_{$invoice->id}/{$invoice->id}.json";
    $disk = Constants::DISK_FILES;

    $maxConsecutivo = 0;
    if (Storage::disk($disk)->exists($path)) {
        $jsonData = json_decode(Storage::disk($disk)->get($path), true);
        if (! empty($jsonData['usuarios'][0]['servicios'][$typeService->elementJson()])) {
            $maxConsecutivo = max(array_column($jsonData['usuarios'][0]['servicios'][$typeService->elementJson()], 'consecutivo'));
        }
    }

    // return $maxConsecutivo;
    // Check database
    $dbMaxConsecutivo = Service::where('invoice_id', $invoice_id)
        ->where('type', $typeService->elementJson())
        ->max('consecutivo') ?? 0;

    return max($maxConsecutivo, $dbMaxConsecutivo) + 1;
}

function reindexConsecutivos(string $invoice_id, TypeServiceEnum $typeService)
{
    // Get services for the invoice and service type, ordered by consecutivo
    $services = Service::where('invoice_id', $invoice_id)
        ->where('type', $typeService->value)
        ->orderBy('consecutivo')
        ->get();

    if ($services->isEmpty()) {
        return; // No services to reindex
    }

    // Reindex consecutivos starting from 1
    $newConsecutivo = 1;
    foreach ($services as $service) {
        if ($service->consecutivo !== $newConsecutivo) {
            $service->update(['consecutivo' => $newConsecutivo]);
        }
        $newConsecutivo++;
    }
}

function openFileJson($path_json)
{
    $disk = Constants::DISK_FILES;
    $storage = Storage::disk($disk);
    $jsonContents = null;

    if ($storage->exists($path_json)) {
        $jsonContents = json_decode($storage->get($path_json), true);
    }

    return $jsonContents;
}

function getModelByTableName($tableName)
{
    $path = app_path('Models');
    if (! File::exists($path)) {
        return null;
    }

    foreach (File::allFiles($path) as $file) {
        $relativePath = $file->getRelativePathname();
        $class = 'App\\Models\\'.str_replace(['/', '.php'], ['\\', ''], $relativePath);

        if (class_exists($class) && is_subclass_of($class, \Illuminate\Database\Eloquent\Model::class)) {
            if ((new $class)->getTable() === $tableName) {
                return $class; // ya es el nombre de clase válido
            }
        }
    }

    return null;
}

function formatDateToArray($date, string $format = 'dmY'): array
{
    if (empty($date)) {
        return array_fill(0, strlen($format), '');
    }

    $parsed = $date instanceof Carbon ? $date : Carbon::parse($date);

    return str_split($parsed->format($format));
}

function formatTimeToArray($time, string $format = 'Hi'): array
{
    if (empty($time)) {
        return array_fill(0, strlen($format), '');
    }
    $parsed = $time instanceof Carbon ? $time : Carbon::parse($time);

    return str_split($parsed->format($format));
}
