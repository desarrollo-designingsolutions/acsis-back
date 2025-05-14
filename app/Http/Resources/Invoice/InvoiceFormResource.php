<?php

namespace App\Http\Resources\Invoice;

use App\Http\Resources\Entity\EntitySelectResource;
use App\Http\Resources\Patient\PatientSelectResource;
use App\Http\Resources\ServiceVendor\ServiceVendorSelectResource;
use App\Http\Resources\TipoNota\TipoNotaSelectResource;
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
            'service_vendor_id' => new ServiceVendorSelectResource($this->serviceVendor),
            'entity_id' => new EntitySelectResource($this->entity),
            'patient_id' => new PatientSelectResource($this->patient),
            'tipo_nota_id' => new TipoNotaSelectResource($this->tipoNota),
            'note_number' => $this->note_number,
            'invoice_number' => $this->invoice_number,
            'radication_number' => $this->radication_number,
            'value_glosa' => $this->value_glosa,
            'value_paid' => $this->value_paid,
            'total' => $this->total,
            'remaining_balance' => $this->remaining_balance,
            'invoice_date' => $this->invoice_date,
            'radication_date' => $this->radication_date,
            'typeable_id' => $this->typeable_id,
            'type' => $this->type,
            'status' => $this->status,
        ];
    }
}
