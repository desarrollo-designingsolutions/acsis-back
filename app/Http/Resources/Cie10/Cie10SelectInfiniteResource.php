<?php

namespace App\Http\Resources\Cie10;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Cie10SelectInfiniteResource extends JsonResource
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
            'title' => $this->codigo.' - '.$this->nombre,
            'codigo' => $this->codigo,
        ];
    }
}
