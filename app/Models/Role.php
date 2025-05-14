<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use Cacheable,HasFactory,HasUuids;

    protected $primaryKey = 'id';

    public function allUsers()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
