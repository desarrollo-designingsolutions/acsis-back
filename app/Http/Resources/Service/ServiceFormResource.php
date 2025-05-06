<?php

namespace App\Http\Resources\Service;

use App\Http\Resources\CupsRips\CupsRipsSelectInfiniteResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceFormResource extends JsonResource
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
            'cups_rip_id' => new CupsRipsSelectInfiniteResource($this->cups_rip),
            'quantity' => $this->quantity,
            'unit_value' => $this->unit_value,
            'total_value' => $this->total_value,
        ];
    }
}
