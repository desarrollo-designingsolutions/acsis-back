<?php

namespace App\Http\Resources\InvoiceSoat;

use App\Http\Resources\Entity\EntitySelectResource;
use App\Http\Resources\ServiceVendor\ServiceVendorSelectResource;
use App\Http\Resources\TypeDocument\TypeDocumentSelectResource;
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
            'policy_number' => $this->policy_number,
            'accident_date' => $this->accident_date,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
