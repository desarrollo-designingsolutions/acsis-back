<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoIdPisis extends Model
{
    use Cacheable, HasFactory, HasUuids;

    protected $table = 'tipo_id_pisis';

    protected $customCachePrefixes = [
        'string:{table}_searchOne*',
    ];
}
