<?php

namespace App\Models;

use App\Enums\Furips2\ServiceTypeEnum;
use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Furips2 extends Model
{
    use Cacheable, HasFactory, HasUuids;

    protected $casts = [
        'serviceType' => ServiceTypeEnum::class,
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function serviceCode()
    {
        return $this->morphTo(__FUNCTION__, 'serviceCode_type', 'serviceCode_id');
    }
}
