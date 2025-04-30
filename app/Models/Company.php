<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use Cacheable, HasFactory, HasUuids, SoftDeletes;

    protected $casts = [
        'is_active' => 'boolean',
        'final_date' => 'datetime',
        'country_id' => 'integer',
        'state_id' => 'integer',
        'city_id' => 'integer',
    ];

    /**
     * Prefijos de caché adicionales específicos para el modelo Company
     */
    // protected $customCachePrefixes = [
    //     'string:{table}_details',       // Clave exacta para detalles de la compañía
    //     'string:{table}_active_ids*',   // Patrón para IDs de compañías activas
    // ];

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }

    public function city()
    {
        return $this->hasOne(City::class, 'id', 'city_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }
}
