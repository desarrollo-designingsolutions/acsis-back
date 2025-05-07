<?php

namespace App\Http\Resources\Invoice;

use App\Enums\Invoice\TypeInvoiceEnum;
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
            $typeCase = collect(TypeInvoiceEnum::cases())
        ->first(fn($c) => (string)$c->value === (string)$this->type);

        return [
            'id' => $this->id,
            'entity_name' => $this->entity?->corporate_name,
            'invoice_number' => $this->invoice_number,
            'type_id'        => $this->type,
            'type_name'      => $typeCase?->description() ?? 'Desconocido',
            'value_paid' => formatNumber($this->value_paid),
            'value_glosa' => formatNumber($this->value_glosa),
            'radication_date' => $this->radication_date,
            'patient_name' => $this->patient?->full_name,
            'is_active' => $this->is_active,
        ];
    }
}
