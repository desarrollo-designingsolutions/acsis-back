<?php

namespace App\Http\Resources\Ffm;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FfmSelectResource extends JsonResource
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
        ];
    }
}
