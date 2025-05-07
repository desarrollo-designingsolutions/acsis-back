<?php

namespace App\Http\Resources\Patient;

use App\Http\Resources\Municipio\MunicipioSelectResource;
use App\Http\Resources\Pais\PaisSelectResource;
use App\Http\Resources\RipsTipoUsuarioVersion2\RipsTipoUsuarioVersion2SelectResource;
use App\Http\Resources\Sexo\SexoSelectResource;
use App\Http\Resources\TipoIdPisis\TipoIdPisisSelectResource;
use App\Http\Resources\ZonaVersion2\ZonaVersion2SelectResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo_id_pisi_id' => new TipoIdPisisSelectResource($this->tipo_id_pisi),
            'document' => $this->document,
            'rips_tipo_usuario_version2_id' => new RipsTipoUsuarioVersion2SelectResource($this->rips_tipo_usuario_version2),
            'sexo_id' => new SexoSelectResource($this->sexo),
            'birth_date' => $this->birth_date,
            'pais_residency_id' => new PaisSelectResource($this->pais_residency),
            'municipio_residency_id' => new MunicipioSelectResource($this->municipio_residency),
            'zona_version2_id' => new ZonaVersion2SelectResource($this->zona_version2),
            'incapacity' => $this->incapacity ? 'Si' : 'No',
            'pais_origin_id' => new PaisSelectResource($this->pais_origin),
            'first_name' => $this->first_name,
            'second_name' => $this->second_name,
            'first_surname' => $this->first_surname,
            'second_surname' => $this->second_surname,
        ];
    }
}
