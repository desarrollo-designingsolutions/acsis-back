<?php

namespace App\Http\Resources\Medicine;

use App\Http\Resources\Cie10\Cie10SelectInfiniteResource;
use App\Http\Resources\ConceptoRecaudo\ConceptoRecaudoSelectResource;
use App\Http\Resources\RipsCausaExternaVersion2\RipsCausaExternaVersion2SelectInfiniteResource;
use App\Http\Resources\TipoMedicamentoPosVersion2\TipoMedicamentoPosVersion2SelectInfiniteResource;
use App\Http\Resources\Umm\UmmSelectInfiniteResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->service->invoice_id,

            'numAutorizacion' => $this->numAutorizacion,
            'idMIPRES' => $this->idMIPRES,
            'fechaDispensAdmon' => $this->fechaDispensAdmon,
            'codDiagnosticoPrincipal_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoPrincipal),
            'codDiagnosticoRelacionado_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionado),
            'tipoMedicamento_id' => new TipoMedicamentoPosVersion2SelectInfiniteResource($this->tipoMedicamento),
            'codTecnologiaSalud' => $this->codTecnologiaSalud,
            'nomTecnologiaSalud' => $this->nomTecnologiaSalud,
            'concentracionMedicamento' => $this->concentracionMedicamento,
            'unidadMedida_id' => new UmmSelectInfiniteResource($this->unidadMedida),
            'formaFarmaceutica' => $this->formaFarmaceutica,
            'unidadMinDispensa' => $this->unidadMinDispensa,
            'cantidadMedicamento' => $this->cantidadMedicamento,
            'diasTratamiento' => $this->diasTratamiento,
            'vrUnitMedicamento' => $this->vrUnitMedicamento,
            'valorPagoModerador' => $this->valorPagoModerador,
            'vrServicio' => $this->vrServicio,
            'conceptoRecaudo_id' => new ConceptoRecaudoSelectResource($this->conceptoRecaudo),
        ];
    }
}
