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
        $radication_date = $this->radication_date ? Carbon::parse($this->radication_date)->format('d-m-Y') : null;
        return [
            'id' => $this->id,
            'serviceVendor_nit' => $this->serviceVendor?->nit,

            'entity_name' => $this->entity?->corporate_name,
            'invoice_number' => $this->invoice_number,
            'type' => $this->type,
            'type_description' => $this->type?->description(),
            'value_paid' => formatNumber($this->value_paid),
            'value_glosa' => formatNumber($this->value_glosa),
            'radication_date' => $radication_date,
            'patient_name' => $this->patient?->full_name,

            'status' => $this->status,
            'status_description' => $this->status?->description(),

            'status_xml' => $this->status_xml,
            'status_xml_backgroundColor' => $this->status_xml?->backgroundColor(),
            'status_xml_description' => $this->status_xml?->description(),

            'path_xml' => $this->path_xml,

            'furips1_id' => $this->furips1?->id,
            'furips2_id' => $this->furips2?->id,
            'furtran_id' => $this->furtran?->id,

        ];
    }
}
