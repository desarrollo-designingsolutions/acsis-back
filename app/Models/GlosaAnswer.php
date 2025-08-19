<?php

namespace App\Models;

use App\Enums\GlosaAnswer\StatusGlosaAnswerEnum;
use App\Traits\Cacheable;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GlosaAnswer extends Model
{
    use Cacheable, HasFactory, HasUuids, Searchable, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'status' => StatusGlosaAnswerEnum::class,
    ];

    public function codeGlosaAnswer()
    {
        return $this->belongsTo(CodeGlosaAnswer::class, 'code_glosa_answer_id');
    }
}
