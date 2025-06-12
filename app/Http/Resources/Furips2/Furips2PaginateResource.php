<?php

namespace App\Http\Resources\Furips2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Furips2PaginateResource extends JsonResource
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
