<?php

namespace App\Http\Resources\GlosaAnswer;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GlosaAnswerPaginateResource extends JsonResource
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
            'value_approved' => formatNumber($this->value_approved),
            'value_accepted' => formatNumber($this->value_accepted),
            'date_answer' => Carbon::parse($this->date_answer)->format('d-m-Y'),
            'observation' => $this->observation,
            'status' => $this->status,
            'status_description' => $this->status?->description(),
        ];
    }
}
