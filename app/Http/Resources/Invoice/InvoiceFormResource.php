<?php

namespace App\Http\Resources\Invoice;

use App\Enums\Invoice\TypeInvoiceEnum;
use App\Http\Resources\Entity\EntitySelectResource;
use App\Http\Resources\ServiceVendor\ServiceVendorSelectResource;
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
        $typeCase = collect(TypeInvoiceEnum::cases())
        ->first(fn($c) => (string)$c->value === (string)$this->type);
        logMessage($this->service_vendor_id);

        return [
            'id' => $this->id,
            'serviceVendor' => new ServiceVendorSelectResource($this->serviceVendors),
            'entity' => new EntitySelectResource($this->entities),
            // 'patient' => new ServiceVendorSelectResource($this->patients),
            'invoice_number' => $this->invoice_number,
            'value_glosa'      => $this->value_glosa,
            'value_approved' => $this->value_approved,
            'invoice_date' => $this->invoice_date,
            'radication_date' => $this->radication_date,
        ];
    }
}
