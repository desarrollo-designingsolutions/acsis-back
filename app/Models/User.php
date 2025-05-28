<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Cacheable, HasApiTokens, HasFactory, HasPermissions, HasRoles, HasUuids, Notifiable;

    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $customCachePrefixes = [
        'string:{table}_list_*',
    ];

    // Sobrescribir el método para personalizar el texto de la acción
    public function getActionDescription($event)
    {
        return match ($event) {
            'created' => 'Creación de un usuario',
            'updated' => 'Actualización de un usuario',
            'deleted' => 'Eliminación de un usuario',
            default => ''
        };
    }

    // Auditoria
    public function getColumnsConfig()
    {
        return [
            'name' => [
                'label' => 'Nombres',
            ],
            'surname' => [
                'label' => 'Apellidos',
            ],
            'email' => [
                'label' => 'Correo electrónico',
            ],
            'role_id' => [
                'label' => 'Rol',
                'model' => 'Role',
                'model_field' => 'description',
            ],
        ];
    }

    // Método de acceso para combinar nombre y apellido
    public function getFullNameAttribute()
    {
        return $this->name.' '.$this->surname;
    }

    public function getAllPermissionsAttribute()
    {
        return $this->getAllPermissions();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function notificaciones()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function serviceVendors()
    {
        return $this->belongsToMany(ServiceVendor::class, 'service_vendor_users', 'user_id', 'service_vendor_id');
    }
}
