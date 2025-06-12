<?php

namespace App\Http\Resources\Decreto780de2026;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Decreto780de2026SelectResource extends JsonResource
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
            'title' => $this->codigo.' - '.$this->descripcion,
        ];
    }
}
