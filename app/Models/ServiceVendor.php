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

    public function ips_no_rep()
    {
        return $this->belongsTo(IpsNoReps::class, 'ips_no_rep_id');
    }
}
