<?php

namespace App\Http\Resources\InvoicePayment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoicePaymentFormResource extends JsonResource
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
            'invoice_id' => $this->invoice_id,
            'value_paid' => $this->value_paid,
            'date_payment' => $this->date_payment,
            'observations' => $this->observations,
            'file' => $this->file,
        ];
    }
}
