<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use Cacheable, HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function entities()
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function patients()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function serviceVendors()
    {
        return $this->belongsTo(ServiceVendor::class, 'service_vendor_id');
    }

    public function invoice_payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }
}
