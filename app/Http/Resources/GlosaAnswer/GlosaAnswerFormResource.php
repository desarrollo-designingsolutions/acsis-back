<?php

namespace App\Http\Resources\GlosaAnswer;

use App\Http\Resources\CodeGlosaAnswer\CodeGlosaAnswerSelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GlosaAnswerFormResource extends JsonResource
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
            'user_id' => $this->user_id,
            'glosa_id' => $this->glosa_id,
            'observation' => $this->observation,
            'file' => $this->file,
            'date_answer' => $this->date_answer,
            'value_approved' => $this->value_approved,
            'value_accepted' => $this->value_accepted,
            'status_id' => $this->status,
            'code_glosa_answer_id' => new CodeGlosaAnswerSelectResource($this->codeGlosaAnswer),
        ];
    }
}
