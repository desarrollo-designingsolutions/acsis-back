<?php

namespace App\Models;

use App\Traits\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use Cacheable, HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'tipo_id_pisi_id',
        'document',
        'rips_tipo_usuario_version2_id',
        'birth_date',
        'sexo_id',
        'pais_residency_id',
        'municipio_residency_id',
        'zona_version2_id',
        'incapacity',
        'pais_origin_id',
        'first_name',
        'second_name',
        'first_surname',
        'second_surname',
        'company_id',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->second_name . ' ' . $this->first_surname . ' ' . $this->second_surname;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function typeDocument()
    {
        return $this->belongsTo(TypeDocument::class);
    }

    public function sexo()
    {
        return $this->belongsTo(Sexo::class);
    }

    public function rips_tipo_usuario_version2()
    {
        return $this->belongsTo(RipsTipoUsuarioVersion2::class);
    }

    public function pais_residency()
    {
        return $this->belongsTo(Pais::class);
    }

    public function municipio_residency()
    {
        return $this->belongsTo(Municipio::class);
    }

    public function zona_version2()
    {
        return $this->belongsTo(ZonaVersion2::class);
    }

    public function tipo_id_pisi()
    {
        return $this->belongsTo(TipoIdPisis::class);
    }

    public function pais_origin()
    {
        return $this->belongsTo(Pais::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'patient_id', 'id');
    }
}
