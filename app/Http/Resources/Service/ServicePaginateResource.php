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
            'unit_value' => formatNumber($this->unit_value),
            'total_value' => formatNumber($this->total_value),
        ];
    }
}
