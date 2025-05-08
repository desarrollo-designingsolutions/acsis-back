<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoRecaudo extends Model
{
    use Cacheable, HasFactory, HasUuids;

    protected $customCachePrefixes = [
        'string:{table}_searchOne*',
    ];
}
