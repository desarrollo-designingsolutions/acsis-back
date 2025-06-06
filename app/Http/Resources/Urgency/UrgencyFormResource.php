<?php

namespace App\Http\Resources\Urgency;

use App\Http\Resources\Cie10\Cie10SelectInfiniteResource;
use App\Http\Resources\CondicionyDestinoUsuarioEgreso\CondicionyDestinoUsuarioEgresoSelectInfiniteResource;
use App\Http\Resources\RipsCausaExternaVersion2\RipsCausaExternaVersion2SelectInfiniteResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UrgencyFormResource extends JsonResource
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
            'causaMotivoAtencion_id' => new RipsCausaExternaVersion2SelectInfiniteResource($this->causaMotivoAtencion),
            'codDiagnosticoPrincipal_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoPrincipal),
            'codDiagnosticoPrincipalE_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoPrincipalE),
            'codDiagnosticoRelacionadoE1_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionadoE1),
            'codDiagnosticoRelacionadoE2_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionadoE2),
            'codDiagnosticoRelacionadoE3_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoRelacionadoE3),
            'condicionDestinoUsuarioEgreso_id' => new CondicionyDestinoUsuarioEgresoSelectInfiniteResource($this->condicionDestinoUsuarioEgreso),
            'codDiagnosticoCausaMuerte_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoCausaMuerte),
            'fechaEgreso' => $this->fechaEgreso,
        ];
    }
}
