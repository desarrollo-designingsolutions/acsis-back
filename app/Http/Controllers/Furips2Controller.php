<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Http\Requests\Furips2\Furips2StoreRequest;
use App\Http\Resources\Furips2\Furips2FormResource;
use App\Http\Resources\Furips2\Furips2PaginateResource;
use App\Http\Resources\Furips2\Furips2TxtResource;
use App\Repositories\Furips2Repository;
use App\Repositories\InvoiceRepository;
use App\Services\CacheService;
use App\Traits\HttpResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Furips2Controller extends Controller
{
    use HttpResponseTrait;

    private $key_redis_project;

    public function __construct(
        protected InvoiceRepository $invoiceRepository,
        protected Furips2Repository $furips2Repository,
        protected QueryController $queryController,
        protected CacheService $cacheService,
    ) {
        $this->key_redis_project = env('KEY_REDIS_PROJECT');
    }

    public function paginate(Request $request)
    {
        return $this->execute(function () use ($request) {
            $data = $this->furips2Repository->paginate($request->all());
            $tableData = Furips2PaginateResource::collection($data);

            return [
                'code' => 200,
                'tableData' => $tableData,
                'lastPage' => $data->lastPage(),
                'totalData' => $data->total(),
                'totalPage' => $data->perPage(),
                'currentPage' => $data->currentPage(),
            ];
        });
    }

    public function create($invoice_id)
    {
        return $this->execute(function () use ($invoice_id) {

            $invoice = $this->invoiceRepository->find($invoice_id, with: [
                'furips1:id,invoice_id,consecutiveClaimNumber',
                'serviceVendor:id,ipsable_type,ipsable_id',
                'serviceVendor.ipsable:id,codigo',
            ], select: [
                'id',
                'service_vendor_id',
            ]);
            $invoice = [
                'id' => $invoice->id,
                'furips1_consecutiveClaimNumber' => $invoice->furips1?->consecutiveClaimNumber,
                'serviceVendor_ipsable_codigo' => $invoice?->serviceVendor?->ipsable?->codigo,

            ];

            $decreto780de2026 = $this->queryController->selectInfiniteDecreto780de2026(request());
            $serviceTypeEnum = $this->queryController->selectServiceTypeEnum(request());

            $ium = $this->queryController->selectInfiniteIum(request());
            $catalogoCum = $this->queryController->selectInfiniteCatalogoCum(request());
            $decreto780de2026 = $this->queryController->selectInfiniteDecreto780de2026(request());

            $codTecnologiaSaludables = [
                [
                    'value' => "App\Models\Ium",
                    'label' => 'Ium',
                    'url' => '/selectInfiniteIum',
                    'arrayInfo' => 'ium',
                    'itemsData' => $ium['ium_arrayInfo'],
                ],
                [
                    'value' => "App\Models\CatalogoCum",
                    'label' => 'CatalogoCum',
                    'url' => '/selectInfiniteCatalogoCum',
                    'arrayInfo' => 'catalogoCum',
                    'itemsData' => $catalogoCum['catalogoCum_arrayInfo'],
                ],
                [
                    'value' => "App\Models\Decreto780de2026",
                    'label' => 'Decreto780de2026',
                    'url' => '/selectInfiniteDecreto780de2026',
                    'arrayInfo' => 'decreto780de2026',
                    'itemsData' => $decreto780de2026['decreto780de2026_arrayInfo'],
                ],
            ];

            return [
                'code' => 200,
                'invoice' => $invoice,
                'codTecnologiaSaludables' => $codTecnologiaSaludables,
                ...$serviceTypeEnum,
                ...$decreto780de2026,
            ];
        });
    }

    public function store(Furips2StoreRequest $request)
    {

        return $this->runTransaction(function () use ($request) {

            $post = $request->except([]);
            $furips2 = $this->furips2Repository->store($post);

            $this->cacheService->clearByPrefix($this->key_redis_project . 'string:invoices_paginate*');

            return [
                'code' => 200,
                'message' => 'Furips2 agregado correctamente',
                'furips2' => $furips2,
            ];
        });
    }

    public function edit($id)
    {
        return $this->execute(function () use ($id) {

            $furips2 = $this->furips2Repository->find($id);
            $form = new Furips2FormResource($furips2);

            $invoice = $this->invoiceRepository->find($furips2->invoice_id, with: [
                'furips1:id,invoice_id,consecutiveClaimNumber',
                'serviceVendor:id,ipsable_type,ipsable_id',
                'serviceVendor.ipsable:id,codigo',
            ], select: [
                'id',
                'service_vendor_id',
            ]);
            $invoice = [
                'id' => $invoice->id,
                'furips1_consecutiveClaimNumber' => $invoice->furips1?->consecutiveClaimNumber,
                'serviceVendor_ipsable_codigo' => $invoice?->serviceVendor?->ipsable?->codigo,

            ];

            $decreto780de2026 = $this->queryController->selectInfiniteDecreto780de2026(request());
            $serviceTypeEnum = $this->queryController->selectServiceTypeEnum(request());

            $ium = $this->queryController->selectInfiniteIum(request());
            $catalogoCum = $this->queryController->selectInfiniteCatalogoCum(request());
            $decreto780de2026 = $this->queryController->selectInfiniteDecreto780de2026(request());

            $codTecnologiaSaludables = [
                [
                    'value' => "App\Models\Ium",
                    'label' => 'Ium',
                    'url' => '/selectInfiniteIum',
                    'arrayInfo' => 'ium',
                    'itemsData' => $ium['ium_arrayInfo'],
                ],
                [
                    'value' => "App\Models\CatalogoCum",
                    'label' => 'CatalogoCum',
                    'url' => '/selectInfiniteCatalogoCum',
                    'arrayInfo' => 'catalogoCum',
                    'itemsData' => $catalogoCum['catalogoCum_arrayInfo'],
                ],
                [
                    'value' => "App\Models\Decreto780de2026",
                    'label' => 'Decreto780de2026',
                    'url' => '/selectInfiniteDecreto780de2026',
                    'arrayInfo' => 'decreto780de2026',
                    'itemsData' => $decreto780de2026['decreto780de2026_arrayInfo'],
                ],
            ];

            return [
                'code' => 200,
                'form' => $form,
                'invoice' => $invoice,
                'codTecnologiaSaludables' => $codTecnologiaSaludables,
                ...$serviceTypeEnum,
                ...$decreto780de2026,
            ];
        });
    }

    public function update(Furips2StoreRequest $request, $id)
    {
        return $this->runTransaction(function () use ($request) {

            $post = $request->except([]);
            $furips2 = $this->furips2Repository->store($post);

            return [
                'code' => 200,
                'message' => 'Furips2 modificada correctamente',

            ];
        });
    }

    public function delete($id)
    {
        return $this->runTransaction(function () use ($id) {
            $furips2 = $this->furips2Repository->find($id);
            if ($furips2) {

                $furips2->delete();

                $msg = 'Registro eliminado correctamente';
            } else {
                $msg = 'El registro no existe';
            }

            return [
                'code' => 200,
                'message' => $msg,
            ];
        }, 200);
    }

    public function downloadTxt($id)
    {
        $furips2 = $this->furips2Repository->find($id);

        $data = [
            'invoice_number' => $furips2->invoice?->invoice_number,
            'consecutiveNumberClaim' => $furips2->consecutiveNumberClaim,
            'serviceType' => $furips2->serviceType?->Value(),
            'serviceCode_id' => $furips2->serviceCode?->codigo,
            'serviceDescription' => $furips2->serviceDescription,
            'serviceQuantity' => $furips2->serviceQuantity,
            'serviceValue' => $furips2->serviceValue,
            'totalFactoryValue' => $furips2->totalFactoryValue,
            'totalClaimedValue' => $furips2->totalClaimedValue,
        ];

        // Generate comma-separated text content
        $textContent = implode(',', array_map(function ($value) {
            return $value ?? '';
        }, $data)) . "\n";

        // Define file name
        $fileName = 'furips2_' . $id . '.txt';

        // Return response with text file for download
        return response($textContent, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
