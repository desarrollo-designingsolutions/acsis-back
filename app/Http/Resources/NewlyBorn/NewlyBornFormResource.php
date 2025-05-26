<?php

namespace App\Http\Resources\NewlyBorn;

use App\Http\Resources\Cie10\Cie10SelectInfiniteResource;
use App\Http\Resources\CondicionyDestinoUsuarioEgreso\CondicionyDestinoUsuarioEgresoSelectInfiniteResource;
use App\Http\Resources\Sexo\SexoSelectResource;
use App\Http\Resources\TipoIdPisis\TipoIdPisisSelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewlyBornFormResource extends JsonResource
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

            'fechaNacimiento' => $this->fechaNacimiento,
            'edadGestacional' => $this->edadGestacional,
            'numConsultasCPrenatal' => $this->numConsultasCPrenatal,
            'codSexoBiologico_id' => new SexoSelectResource($this->codSexoBiologico),
            'peso' => $this->peso,
            'codDiagnosticoPrincipal_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoPrincipal),
            'condicionDestinoUsuarioEgreso_id' => new CondicionyDestinoUsuarioEgresoSelectInfiniteResource($this->condicionDestinoUsuarioEgreso),
            'codDiagnosticoCausaMuerte_id' => new Cie10SelectInfiniteResource($this->codDiagnosticoCausaMuerte),
            'fechaEgreso' => $this->fechaEgreso,
            'tipoDocumentoIdentificacion_id' => new TipoIdPisisSelectResource($this->tipoDocumentoIdentificacion),
            'numDocumentoIdentificacion' => $this->numDocumentoIdentificacion,
        ];
    }
}
