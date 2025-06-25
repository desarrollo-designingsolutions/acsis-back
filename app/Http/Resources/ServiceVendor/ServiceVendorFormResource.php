<?php

namespace App\Http\Resources\ServiceVendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceVendorFormResource extends JsonResource
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
            'name' => $this->name,
            'nit' => $this->nit,
            'phone' => $this->phone,
            'address' => $this->address,
            'email' => $this->email,
            'type_vendor_id' => $this->type_vendor_id,
            'ipsable_type' => $this->ipsable_type,
            'ipsable_id' => [
                'value' => $this->ipsable_id,
                'title' => $this->getIpsableIdTitle(),
                'codigo' => $this->ipsable?->codigo,
                'nit' => $this->ipsable?->nroIDPrestador ?? $this->ipsable?->nit,
            ],
        ];
    }

    /**
     * Get the title for ipsable_id.
     */
    private function getIpsableIdTitle(): string
    {
        if (! $this->ipsable) {
            return 'No asignado';
        }

        return $this->ipsable->codigo . ' - ' . $this->ipsable->nombre;
    }
}
