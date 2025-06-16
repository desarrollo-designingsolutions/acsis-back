<?php

namespace App\Http\Resources\Entity;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityFormResource extends JsonResource
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
            'corporate_name' => $this->corporate_name,
            'nit' => $this->nit,
            'address' => $this->address,
            'phone' => $this->phone,
            'email' => $this->email,
            'type_entity_id' => $this->type_entity_id,
            'insuranceCode' => $this->insuranceCode,
        ];
    }
}
