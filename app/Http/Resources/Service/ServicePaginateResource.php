<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServicePaginateResource extends JsonResource
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
            'invoice_id' => $this->invoice_id,
            'cups_rip_codigo' => $this->cups_rip?->codigo,
            'cups_rip_nombre' => $this->cups_rip?->nombre,
            'quantity' => $this->quantity,
            'codigo_servicio' => $this->codigo_servicio,
            'nombre_servicio' => $this->nombre_servicio,
            'unit_value' => formatNumber($this->unit_value),
            'total_value' => formatNumber($this->total_value),
            'type' => $this->type,
            'type_description' => $this->type->description(),
            'total_value_origin' => $this->total_value,
        ];
    }
}
