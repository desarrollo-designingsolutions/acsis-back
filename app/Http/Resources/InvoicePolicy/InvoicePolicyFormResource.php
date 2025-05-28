<?php

namespace App\Http\Resources\InvoicePolicy;

use App\Http\Resources\InsuranceStatus\InsuranceStatusSelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoicePolicyFormResource extends JsonResource
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
            'insurance_statuse_id' => new InsuranceStatusSelectResource($this->insurance_statuse),
        ];
    }
}
