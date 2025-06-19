<?php

namespace App\Models;

use App\Enums\Fultran\RgResponseEnum;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fultran extends Model
{
    use Cacheable, HasFactory, HasUuids;

    protected $casts = [
        'rgResponse' => RgResponseEnum::class,
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function claimantIdType(): BelongsTo
    {
        return $this->belongsTo(TipoIdPisis::class, 'claimantIdType_id', 'id');
    }

    public function claimantDepartmentCode(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'claimantDepartmentCode_id', 'id');
    }

    public function pickupDepartmentCode(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'pickupDepartmentCode_id', 'id');
    }

    public function transferPickupDepartmentCode(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'transferPickupDepartmentCode_id', 'id');
    }

    public function claimantMunicipalityCode(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'claimantMunicipalityCode_id', 'id');
    }

    public function pickupMunicipalityCode(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'pickupMunicipalityCode_id', 'id');
    }

    public function transferPickupMunicipalityCode(): BelongsTo
    {
        return $this->belongsTo(Municipio::class, 'transferPickupMunicipalityCode_id', 'id');
    }

    public function ipsReceptionHabilitationCode(): BelongsTo
    {
        return $this->belongsTo(IpsCodHabilitacion::class, 'ipsReceptionHabilitationCode_id', 'id');
    }
}
