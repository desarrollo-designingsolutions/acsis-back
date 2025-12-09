<?php

namespace App\Http\Requests\Patient;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule; // <--- IMPORTANTE: Importar Rule

class PatientStoreRequest extends FormRequest
{
    public function rules(): array
    {
        // 1. Obtener el ID de la empresa.
        // Si viene en el request usa: $this->company_id
        $companyId = $this->company_id;

        $rules = [
            'tipo_id_pisi_id' => 'required',
            'document' => [
                'required',
                // 2. Regla compuesta
                Rule::unique('patients', 'document') // 'patients' es la tabla
                    ->where(function ($query) use ($companyId) {
                        return $query->where('tipo_id_pisi_id', $this->tipo_id_pisi_id)
                                     ->where('company_id', $companyId)
                                     ->whereNull('deleted_at'); // Si usas SoftDeletes, agrega esto
                    }),
            ],
            'rips_tipo_usuario_version2_id' => 'required',
            'birth_date' => 'required|date',
            'sexo_id' => 'required',
            'pais_residency_id' => 'required',
            'incapacity' => 'required',
            'pais_origin_id' => 'required',
            'first_name' => 'required',
            'first_surname' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            // ... tus mensajes existentes ...
            'tipo_id_pisi_id.required' => 'El campo es obligatorio',
            'document.required' => 'El campo es obligatorio',

            // 3. Mensaje personalizado para el error de duplicado
            'document.unique' => 'Ya existe un paciente con este número de documento y tipo de identificación en la empresa.',

            'rips_tipo_usuario_version2_id.required' => 'El campo es obligatorio',
            'birth_date.required' => 'El campo es obligatorio',
            'birth_date.date' => 'El campo debe ser una fecha',
            'sexo_id.required' => 'El campo es obligatorio',
            'pais_residency_id.required' => 'El campo es obligatorio',
            'incapacity.required' => 'El campo es obligatorio',
            'pais_origin_id.required' => 'El campo es obligatorio',
            'first_name.required' => 'El campo es obligatorio',
            'first_surname.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('tipo_id_pisi_id')) {
            $merge['tipo_id_pisi_id'] = getValueSelectInfinite($this->tipo_id_pisi_id);
        }
        if ($this->has('rips_tipo_usuario_version2_id')) {
            $merge['rips_tipo_usuario_version2_id'] = getValueSelectInfinite($this->rips_tipo_usuario_version2_id);
        }
        if ($this->has('sexo_id')) {
            $merge['sexo_id'] = getValueSelectInfinite($this->sexo_id);
        }
        if ($this->has('pais_residency_id')) {
            $merge['pais_residency_id'] = getValueSelectInfinite($this->pais_residency_id);
        }
        if ($this->has('municipio_residency_id')) {
            $merge['municipio_residency_id'] = getValueSelectInfinite($this->municipio_residency_id);
        }
        if ($this->has('zona_version2_id')) {
            $merge['zona_version2_id'] = getValueSelectInfinite($this->zona_version2_id);
        }
        if ($this->has('pais_origin_id')) {
            $merge['pais_origin_id'] = getValueSelectInfinite($this->pais_origin_id);
        }

        $merge['incapacity'] = $this->incapacity === 'Si' ? 1 : 0;

        $this->merge($merge);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'message' => Constants::ERROR_MESSAGE_VALIDATION_BACK,
            'errors' => $validator->errors(),
        ], 422));
    }
}
