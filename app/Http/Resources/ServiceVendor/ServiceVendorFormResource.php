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
        ];
    }
}
