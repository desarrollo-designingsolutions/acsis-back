<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    use Cacheable, HasFactory, HasUuids, SoftDeletes;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function typeEntity()
    {
        return $this->belongsTo(TypeEntity::class, 'type_entity_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, "entity_id");
    }
}
