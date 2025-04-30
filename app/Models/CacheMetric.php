<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CacheMetric extends Model
{
    protected $fillable = ['source', 'response_time'];

    public $timestamps = ['created_at'];

    const UPDATED_AT = null;
}
