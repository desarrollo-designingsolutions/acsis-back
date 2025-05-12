<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceListResource extends JsonResource
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
            'entity_name' => $this->entity?->corporate_name,
            'invoice_number' => $this->invoice_number,
            'type' => $this->type,
            'type_description' =>  $this->type?->description(),
            'value_paid' => formatNumber($this->value_paid),
            'value_glosa' => formatNumber($this->value_glosa),
            'radication_date' => $this->radication_date,
            'patient_name' => $this->patient?->full_name,
            'status' => $this->status,
            'status_description' => $this->status?->description(),
        ];
    }
}
