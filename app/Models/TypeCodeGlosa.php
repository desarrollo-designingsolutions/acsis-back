<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeCodeGlosa extends Model
{
    use HasFactory;
    use HasUuids;

    /**
     * Relación uno a muchos con GeneralCodeGlosa.
     */
    public function generalCodeGlosas()
    {
        return $this->hasMany(GeneralCodeGlosa::class, 'type_code_glosa_id');
    }
}
