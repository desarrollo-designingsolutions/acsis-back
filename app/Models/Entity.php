<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    public function typeEntity()
    {
        return $this->belongsTo(TypeEntity::class, 'type_entity_id');
    }
}
