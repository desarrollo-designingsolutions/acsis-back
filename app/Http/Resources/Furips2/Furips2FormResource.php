<?php

namespace App\Http\Resources\Furips2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Furips2FormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $serviceCode_title = isset($this->serviceCode?->nombre) ? $this->serviceCode?->nombre : $this->serviceCode?->descripcion;

        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,

            'consecutiveNumberClaim' => $this->consecutiveNumberClaim,
            'serviceType' => $this->serviceType,
            'serviceCode_type' => $this->serviceCode_type,

            'serviceCode_id' => $this->serviceCode_type ? [
                'value' => $this->serviceCode_id,
                'title' => $this->serviceCode?->codigo.' - '.$serviceCode_title,
                'codigo' => $this->serviceCode?->codigo,
            ] : null,

            'serviceDescription' => $this->serviceDescription,
            'serviceQuantity' => $this->serviceQuantity,
            'serviceValue' => $this->serviceValue,
            'totalFactoryValue' => $this->totalFactoryValue,
            'totalClaimedValue' => $this->totalClaimedValue,
            'victimResidenceAddress' => $this->victimResidenceAddress,

        ];
    }
}
