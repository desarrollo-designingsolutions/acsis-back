<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use Cacheable, HasFactory, HasUuids, SoftDeletes;

    protected function casts(): array
    {
        return [
            'codigo' => 'integer',
        ];
    }
}
