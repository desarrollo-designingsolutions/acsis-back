<?php

namespace App\Http\Resources\IpsNoReps;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IpsNoRepsSelectInfiniteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->id,
            'title' => $this->nit.' / '.$this->nombre,
            'codigo' => $this->codigo,
        ];
    }
}
