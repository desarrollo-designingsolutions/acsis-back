<?php

namespace App\Http\Resources\Hospitalization;

use App\Http\Resources\Cie10\Cie10SelectInfiniteResource;
use App\Http\Resources\CondicionyDestinoUsuarioEgreso\CondicionyDestinoUsuarioEgresoSelectInfiniteResource;
use App\Http\Resources\RipsCausaExternaVersion2\RipsCausaExternaVersion2SelectInfiniteResource;
use App\Http\Resources\ViaIngresoUsuario\ViaIngresoUsuarioSelectInfiniteResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HospitalizationFormResource extends JsonResource
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

            'viaIngresoServicioSalud_id' => new ViaIngresoUsuarioSelectInfiniteResource($this->viaIngresoServicioSalud),
            'fechaInicioAtencion' => $this->fechaInicioAtencion,
            'numAutorizacion' => $this->numAutorizacion,
            'causaMotivoAtencion_id' => new RipsCausaExternaVersion2SelectInfiniteResource($this->causaMotivoAtencion),
            'codDiagnosticoPrincipal_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoPrincipal),
            'codDiagnosticoPrincipalE_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoPrincipalE),
            'codDiagnosticoRelacionadoE1_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionadoE1),
            'codDiagnosticoRelacionadoE2_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionadoE2),
            'codDiagnosticoRelacionadoE3_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionadoE3),
            'codComplicacion_id' => new Cie10SelectInfiniteResource($this->codComplicacion),
            'condicionDestinoUsuarioEgreso_id' => new CondicionyDestinoUsuarioEgresoSelectInfiniteResource($this->condicionDestinoUsuarioEgreso),
            'codDiagnosticoCausaMuerte_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoCausaMuerte),
            'fechaEgreso' => $this->fechaEgreso,
        ];
    }
}
