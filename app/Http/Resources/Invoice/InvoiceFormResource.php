<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceFormResource extends JsonResource
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
            'entity_name' => $this->entities?->corporate_name,
            'invoice_number' => $this->invoice_number,
            'type' => $this->type,
            'value_approved' => $this->value_approved,
            'value_glosa' => $this->value_glosa,
            'radication_date' => $this->radication_date,
            'patient_id' => $this->patients?->full_name,
            'is_active' => $this->is_active,
        ];
    }
}
