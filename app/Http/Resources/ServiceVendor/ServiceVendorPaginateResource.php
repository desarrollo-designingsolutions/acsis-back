<?php

namespace App\Http\Resources\ServiceVendor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceVendorPaginateResource extends JsonResource
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
            'address' => $this->address,
            'type_vendor_name' => $this->type_vendor?->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
        ];
    }
}
