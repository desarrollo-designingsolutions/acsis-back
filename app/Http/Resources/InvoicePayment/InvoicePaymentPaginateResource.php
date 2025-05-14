<?php

namespace App\Http\Resources\InvoicePayment;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoicePaymentPaginateResource extends JsonResource
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
            'value_paid' => formatNumber($this->value_paid),
            'date_payment' => Carbon::parse($this->date_payment)->format('d-m-Y'),
            'observations' => $this->observations,
        ];
    }
}
