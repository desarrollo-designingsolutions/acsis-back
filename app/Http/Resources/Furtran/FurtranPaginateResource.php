<?php

namespace App\Http\Resources\Furtran;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FurtranPaginateResource extends JsonResource
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
        ];
    }
}
