<?php

namespace App\Http\Resources\Fultran;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FultranPaginateResource extends JsonResource
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
