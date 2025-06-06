<?php

namespace App\Http\Resources\Medicine;

use App\Http\Resources\Cie10\Cie10SelectInfiniteResource;
use App\Http\Resources\ConceptoRecaudo\ConceptoRecaudoSelectResource;
use App\Http\Resources\Dci\DciSelectResource;
use App\Http\Resources\Ffm\FfmSelectResource;
use App\Http\Resources\TipoIdPisis\TipoIdPisisSelectResource;
use App\Http\Resources\TipoMedicamentoPosVersion2\TipoMedicamentoPosVersion2SelectInfiniteResource;
use App\Http\Resources\Umm\UmmSelectInfiniteResource;
use App\Http\Resources\Upr\UprSelectResource;
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

            'codTecnologiaSaludable_type' => $this->codTecnologiaSaludable_type,

            'codTecnologiaSaludable_id' => [
                'value' => $this->codTecnologiaSaludable_id,
                'title' => $this->getCodTecnologiaSaludableTitle(),
                'codigo' => $this->codTecnologiaSaludable?->codigo,
                'nit' => $this->codTecnologiaSaludable?->nroIDPrestador ?? $this->codTecnologiaSaludable?->nit,
            ],

            'nomTecnologiaSalud_id' => new DciSelectResource($this->nomTecnologiaSalud),
            'concentracionMedicamento' => $this->concentracionMedicamento,
            'unidadMedida_id' => new UmmSelectInfiniteResource($this->unidadMedida),
            'formaFarmaceutica_id' => new FfmSelectResource($this->formaFarmaceutica),
            'unidadMinDispensa_id' => new UprSelectResource($this->unidadMinDispensa),
            'cantidadMedicamento' => $this->cantidadMedicamento,
            'diasTratamiento' => $this->diasTratamiento,
            'vrUnitMedicamento' => $this->vrUnitMedicamento,
            'valorPagoModerador' => $this->valorPagoModerador,
            'vrServicio' => $this->vrServicio,
            'conceptoRecaudo_id' => new ConceptoRecaudoSelectResource($this->conceptoRecaudo),
            'tipoDocumentoIdentificacion_id' => new TipoIdPisisSelectResource($this->tipoDocumentoIdentificacion),
            'numDocumentoIdentificacion' => $this->numDocumentoIdentificacion,
            'numFEVPagoModerador' => $this->numFEVPagoModerador,
        ];
    }

    /**
     * Get the title for codTecnologiaSaludable_id.
     */
    private function getCodTecnologiaSaludableTitle(): string
    {
        if (! $this->codTecnologiaSaludable) {
            return 'No asignado';
        }

        if ($this->codTecnologiaSaludable->nroIDPrestador) {
            return $this->codTecnologiaSaludable->nroIDPrestador;
        }

        return $this->codTecnologiaSaludable->nit.' - '.$this->codTecnologiaSaludable->nombre;
    }
}
