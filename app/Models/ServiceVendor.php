<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceVendor extends Model
{
    use Cacheable, HasFactory, HasUuids, SoftDeletes;

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function type_vendor()
    {
        return $this->belongsTo(TypeVendor::class);
    }

    public function ips_cod_habilitacion()
    {
        return $this->belongsTo(IpsCodHabilitacion::class, 'ips_cod_habilitacion_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'service_vendor_users', 'service_vendor_id', 'user_id');
    }

    public function ipsable()
    {
        return $this->morphTo(__FUNCTION__, 'ipsable_type', 'ipsable_id');
    }


    public function invoices()
    {
        return $this->hasMany(Invoice::class, "service_vendor_id");
    }
}
