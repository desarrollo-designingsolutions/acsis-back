<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeGlosa extends Model
{
    use Cacheable, HasFactory, HasUuids;

    protected $customCachePrefixes = [
        'string:{table}_list*',
    ];

    /**
     * Relación muchos a uno con GeneralCodeGlosa.
     */
    public function generalCodeGlosa()
    {
        return $this->belongsTo(GeneralCodeGlosa::class, 'general_code_glosa_id');
    }
}
