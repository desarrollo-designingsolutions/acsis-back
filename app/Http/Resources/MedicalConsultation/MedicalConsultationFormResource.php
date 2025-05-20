<?php

namespace App\Http\Resources\MedicalConsultation;

use App\Http\Resources\Cie10\Cie10SelectInfiniteResource;
use App\Http\Resources\ConceptoRecaudo\ConceptoRecaudoSelectResource;
use App\Http\Resources\CupsRips\CupsRipsSelectInfiniteResource;
use App\Http\Resources\GrupoServicio\GrupoServicioSelectInfiniteResource;
use App\Http\Resources\ModalidadAtencion\ModalidadAtencionSelectInfiniteResource;
use App\Http\Resources\RipsCausaExternaVersion2\RipsCausaExternaVersion2SelectInfiniteResource;
use App\Http\Resources\RipsFinalidadConsultaVersion2\RipsFinalidadConsultaVersion2SelectInfiniteResource;
use App\Http\Resources\RipsTipoDiagnosticoPrincipalVersion2\RipsTipoDiagnosticoPrincipalVersion2SelectInfiniteResource;
use App\Http\Resources\Servicio\ServicioSelectInfiniteResource;
use App\Http\Resources\TipoIdPisis\TipoIdPisisSelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalConsultationFormResource extends JsonResource
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
            'fechaInicioAtencion' => $this->fechaInicioAtencion,
            'numAutorizacion' => $this->numAutorizacion,

            'codConsulta_id' => new CupsRipsSelectInfiniteResource($this->codConsulta),
            'modalidadGrupoServicioTecSal_id' => new ModalidadAtencionSelectInfiniteResource($this->modalidadGrupoServicioTecSal),
            'grupoServicios_id' => new GrupoServicioSelectInfiniteResource($this->grupoServicios),
            'codServicio_id' => new ServicioSelectInfiniteResource($this->codServicio),
            'finalidadTecnologiaSalud_id' => new RipsFinalidadConsultaVersion2SelectInfiniteResource($this->finalidadTecnologiaSalud),
            'causaMotivoAtencion_id' => new RipsCausaExternaVersion2SelectInfiniteResource($this->causaMotivoAtencion),
            'codDiagnosticoPrincipal_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoPrincipal),
            'codDiagnosticoRelacionado1_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionado1),
            'codDiagnosticoRelacionado2_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionado2),
            'codDiagnosticoRelacionado3_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionado3),
            'tipoDiagnosticoPrincipal_id' => new RipsTipoDiagnosticoPrincipalVersion2SelectInfiniteResource($this->tipoDiagnosticoPrincipal),
            'valorPagoModerador' => $this->valorPagoModerador,
            'vrServicio' => $this->vrServicio,

            'conceptoRecaudo_id' => new ConceptoRecaudoSelectResource($this->conceptoRecaudo),
            'tipoDocumentoIdentificacion_id' => new TipoIdPisisSelectResource($this->tipoDocumentoIdentificacion),
            'numDocumentoIdentificacion' => $this->numDocumentoIdentificacion,
            'numFEVPagoModerador' => $this->numFEVPagoModerador,

        ];
    }
}
