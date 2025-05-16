<?php

namespace App\Http\Resources\RipsTipoDiagnosticoPrincipalVersion2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RipsTipoDiagnosticoPrincipalVersion2SelectInfiniteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->id,
            'title' => $this->codigo.' - '.$this->nombre,
            'codigo' => $this->codigo,
        ];
    }
}
