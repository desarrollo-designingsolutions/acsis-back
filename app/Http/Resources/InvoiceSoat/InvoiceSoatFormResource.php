<?php

namespace App\Http\Resources\InvoiceSoat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceSoatFormResource extends JsonResource
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
            'soat_number' => $this->soat_number,
            'accident_date' => $this->accident_date,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
