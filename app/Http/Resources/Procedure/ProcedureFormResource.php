<?php

namespace App\Http\Resources\Procedure;

use App\Http\Resources\Cie10\Cie10SelectInfiniteResource;
use App\Http\Resources\ConceptoRecaudo\ConceptoRecaudoSelectResource;
use App\Http\Resources\CupsRips\CupsRipsSelectInfiniteResource;
use App\Http\Resources\GrupoServicio\GrupoServicioSelectInfiniteResource;
use App\Http\Resources\ModalidadAtencion\ModalidadAtencionSelectInfiniteResource;
use App\Http\Resources\RipsCausaExternaVersion2\RipsCausaExternaVersion2SelectInfiniteResource;
use App\Http\Resources\RipsFinalidadConsultaVersion2\RipsFinalidadConsultaVersion2SelectInfiniteResource;
use App\Http\Resources\Servicio\ServicioSelectInfiniteResource;
use App\Http\Resources\TipoIdPisis\TipoIdPisisSelectResource;
use App\Http\Resources\ViaIngresoUsuario\ViaIngresoUsuarioSelectInfiniteResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcedureFormResource extends JsonResource
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
            'idMIPRES' => $this->idMIPRES,
            'numAutorizacion' => $this->numAutorizacion,

            'codProcedimiento_id' => new CupsRipsSelectInfiniteResource($this->codProcedimiento),
            'viaIngresoServicioSalud_id' => new ViaIngresoUsuarioSelectInfiniteResource($this->viaIngresoServicioSalud),
            'modalidadGrupoServicioTecSal_id' => new ModalidadAtencionSelectInfiniteResource($this->modalidadGrupoServicioTecSal),
            'grupoServicios_id' => new GrupoServicioSelectInfiniteResource($this->grupoServicios),
            'codServicio_id' => new ServicioSelectInfiniteResource($this->codServicio),
            'finalidadTecnologiaSalud_id' => new RipsFinalidadConsultaVersion2SelectInfiniteResource($this->finalidadTecnologiaSalud),
            'causaMotivoAtencion_id' => new RipsCausaExternaVersion2SelectInfiniteResource($this->causaMotivoAtencion),
            'codDiagnosticoPrincipal_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoPrincipal),
            'codDiagnosticoRelacionado_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionado),
            'codComplicacion_id' => new Cie10SelectInfiniteResource($this->codComplicacion),
            'valorPagoModerador' => $this->valorPagoModerador,
            'vrServicio' => $this->vrServicio,

            'conceptoRecaudo_id' => new ConceptoRecaudoSelectResource($this->conceptoRecaudo),
            'tipoDocumentoIdentificacion_id' => new TipoIdPisisSelectResource($this->tipoDocumentoIdentificacion),
            'numDocumentoIdentificacion' => $this->numDocumentoIdentificacion,
            'numFEVPagoModerador' => $this->numFEVPagoModerador,
        ];
    }
}
