<?php

namespace App\Http\Resources\Invoice;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

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
            'type_description' => $this->type?->description(),
            'value_paid' => formatNumber($this->value_paid),
            'value_glosa' => formatNumber($this->value_glosa),
            'radication_date' => Carbon::parse($this->radication_date)->format('d-m-Y'),
            'patient_name' => $this->patient?->full_name,

            'status' => $this->status,
            'status_description' => $this->status?->description(),

            'status_xml' => $this->status_xml,
            'status_xml_backgroundColor' => $this->status_xml->backgroundColor(),
            'status_xml_description' => $this->status_xml->description(),

            'path_xml' => $this->path_xml,

        ];
    }
}
