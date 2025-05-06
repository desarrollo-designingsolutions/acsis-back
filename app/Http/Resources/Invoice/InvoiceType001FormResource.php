<?php

namespace App\Http\Resources\Invoice;

use App\Enums\Invoice\TypeInvoiceEnum;
use App\Http\Resources\Entity\EntitySelectResource;
use App\Http\Resources\ServiceVendor\ServiceVendorSelectResource;
use App\Http\Resources\TypeDocument\TypeDocumentSelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceType001FormResource extends JsonResource
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
            'serviceVendor' => new ServiceVendorSelectResource($this->serviceVendors),
            'entity' => new EntitySelectResource($this->entities),
            'invoice_number' => $this->invoice_number,
            'radication_number' => $this->radication_number,
            'value_glosa'      => $this->value_glosa,
            'value_approved' => $this->value_approved,
            'invoice_date' => $this->invoice_date,
            'radication_date' => $this->radication_date,
            'patient_id' => $this->patients?->id,
            'typeDocument' => new TypeDocumentSelectResource($this->patients?->typeDocument),
            'document' => $this->patients?->document,
            'first_name' => $this->patients?->first_name,
            'second_name' => $this->patients?->second_name,
            'first_surname' => $this->patients?->first_surname,
            'second_surname' => $this->patients?->second_surname,
        ];
    }
}
