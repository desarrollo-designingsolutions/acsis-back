<?php

use App\Http\Controllers\CacheController;
use App\Models\Invoice;
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

    // $id = '1';
    // $invoice = Invoice::find($id);
    $data = [];

    $pdf = \PDF::loadView('Exports/Furips1/Furips1ExportPdf', compact('data'));

    return $pdf->stream();
});
