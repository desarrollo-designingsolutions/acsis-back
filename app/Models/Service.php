<?php

namespace App\Models;

use App\Enums\Service\TypeServiceEnum;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use Cacheable, HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'type' => TypeServiceEnum::class,
    ];

    /**
     * Boot del modelo para registrar eventos.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($service) {
            Invoice::updateTotalFromServices($service->invoice_id);
        });

        static::updated(function ($service) {
            Invoice::updateTotalFromServices($service->invoice_id);
        });

        static::deleted(function ($service) {
            Invoice::updateTotalFromServices($service->invoice_id);
        });

        static::saved(function ($service) {
            Invoice::updateTotalFromServices($service->invoice_id);
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function serviceable()
    {
        return $this->morphTo();
    }

    public function glosas()
    {
        return $this->hasMany(Glosa::class);
    }

    public function updateValueGosaFromServices() {}
}
