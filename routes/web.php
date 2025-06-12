<?php

use App\Enums\Furips1\VictimConditionEnum;
use App\Http\Controllers\CacheController;
use App\Models\Invoice;
use App\Models\Sexo;
use App\Models\User;
use App\Notifications\BellNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $user = User::first();

    // Enviar notificación
    $user->notify(new BellNotification([
        'title' => 'hola',
        'subtitle' => 'chao',
    ]));

    return view('welcome');
});

Route::get('/cache-keys', [CacheController::class, 'listCacheKeys']);
Route::get('/cache-clear', [CacheController::class, 'clearAllCache']);

Route::get('/pdf1', function () {

    $select = Sexo::select('codigo')->get();

    $select_sexo = $select->pluck('codigo')->toArray();

    $id = '01971870-edd8-736e-b5b4-191eddc275ab';
    $invoice = Invoice::find($id);

    $reformatted = \Carbon\Carbon::parse($invoice['radication_date'])->format('dmY'); // "26062025"

    // Paso 2: convertir en array de dígitos
    $asArray = str_split($reformatted);
    $invoice['radication_date'] = $asArray;
    $reformatted = \Carbon\Carbon::parse($invoice->patient->birth_date)->format('dmY');

    $asArray = str_split($reformatted);
    $invoice['patient']['birth_date'] = $asArray;

    $birthMask = ['D', 'D', 'M', 'M', 'A', 'A', 'A', 'A'];

    $victimConditions = collect(VictimConditionEnum::cases())->map(function ($case) {
        return [
            'value' => $case->value,
            'label' => $case->description(),
        ];
    })->toArray();

    // return $invoice->furips1;

    $data = [
        'radication_date' => $invoice->radication_date,
        'birthMask' => $birthMask,
        'radication_number' => $invoice->radication_number,
        'tipo_nota_id' => $invoice->tipo_nota_id,
        'note_number' => $invoice->note_number,
        'invoice_number' => $invoice->invoice_number,
        'service_vendor_name' => $invoice->serviceVendor->name,
        'service_vendor_nit' => $invoice->serviceVendor->nit,
        'service_vendor_ipsable' => $invoice->serviceVendor->ipsable->codigo,
        'patient_first_surname' => $invoice->patient->first_surname,
        'patient_second_surname' => $invoice->patient->second_surname,
        'patient_first_name' => $invoice->patient->first_name,
        'patient_second_name' => $invoice->patient->second_name,
        'patient_document' => $invoice->patient->document,
        'patient_birth_date' => $invoice->patient->birth_date,
        'select_sexo' => $select_sexo,
        'sexo_code' => $invoice->patient->sexo->codigo,
        'patient_recidence_address' => $invoice->furips1->victimResidenceAddress,
        'patient_phone' => $invoice->furips1->victimPhone,
        'victim_conditions' => $victimConditions,
        'patient_condition' => $invoice->furips1->victimCondition,
        'patient_department_name' => $invoice->patient->pais_residency->nombre,
        'patient_department_code' => $invoice->patient->pais_residency->codigo,
        'patient_municipio_name' => $invoice->patient->municipio_residency->nombre,
        'patient_municipio_code' => $invoice->patient->municipio_residency->codigo,
    ];
    // return $data;

    $pdf = \PDF::loadView('Exports/Furips1/Furips1ExportPdf', compact('data'));

    return $pdf->stream();
});
