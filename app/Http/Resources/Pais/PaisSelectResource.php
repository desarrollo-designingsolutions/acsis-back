<?php

namespace App\Http\Resources\Pais;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaisSelectResource extends JsonResource
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
            'extra_II' => $this->extra_II,
        ];
    }
}
