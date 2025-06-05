<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralCodeGlosa extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * Relación muchos a uno con TypeCodeGlosa.
     */
    public function typeCodeGlosa()
    {
        return $this->belongsTo(TypeCodeGlosa::class, 'type_code_glosa_id');
    }

    /**
     * Relación uno a muchos con CodeGlosa.
     */
    public function codeGlosas()
    {
        return $this->hasMany(CodeGlosa::class, 'general_code_glosa_id');
    }
}
