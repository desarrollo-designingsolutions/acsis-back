<?php

namespace App\Http\Resources\User;

use App\Http\Resources\ServiceVendor\ServiceVendorSelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFormResource extends JsonResource
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
            'surname' => $this->surname,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'company_id' => $this->company_id,
            'service_vendor_ids' => ServiceVendorSelectResource::collection($this->serviceVendors),
        ];
    }
}
