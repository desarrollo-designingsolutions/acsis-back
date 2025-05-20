<?php

namespace App\Http\Resources\OtherService;

use App\Http\Resources\ConceptoRecaudo\ConceptoRecaudoSelectResource;
use App\Http\Resources\CupsRips\CupsRipsSelectInfiniteResource;
use App\Http\Resources\TipoIdPisis\TipoIdPisisSelectResource;
use App\Http\Resources\TipoOtrosServicios\TipoOtrosServiciosSelectResource;
use App\Models\CupsRips;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OtherServiceFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $cupsRips = CupsRips::where('codigo', $this->codTecnologiaSalud)->first();
        $codTecnologiaSalud = new CupsRipsSelectInfiniteResource($cupsRips);

        return [
            'id' => $this->id,
            'invoice_id' => $this->service->invoice_id,
            'numAutorizacion' => $this->numAutorizacion,
            'idMIPRES' => $this->idMIPRES,
            'fechaSuministroTecnologia' => $this->fechaSuministroTecnologia,
            'tipoOS_id' => new TipoOtrosServiciosSelectResource($this->tipoOtrosServicio),
            'codTecnologiaSalud' => $codTecnologiaSalud,
            'nomTecnologiaSalud' => $this->nomTecnologiaSalud,
            'cantidadOS' => $this->cantidadOS,
            'vrUnitOS' => $this->vrUnitOS,
            'valorPagoModerador' => $this->valorPagoModerador,
            'vrServicio' => $this->vrServicio,
            'conceptoRecaudo_id' => new ConceptoRecaudoSelectResource($this->conceptoRecaudo),
            'tipoDocumentoIdentificacion_id' => new TipoIdPisisSelectResource($this->tipoDocumentoIdentificacion),
            'numDocumentoIdentificacion' => $this->numDocumentoIdentificacion,
            'numFEVPagoModerador' => $this->numFEVPagoModerador,
        ];
    }
}
