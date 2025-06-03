<?php

namespace App\Http\Requests\Patient;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PatientStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'tipo_id_pisi_id' => 'required',
            'document' => 'required',
            'rips_tipo_usuario_version2_id' => 'required',
            'birth_date' => 'required|date',
            'sexo_id' => 'required',
            'pais_residency_id' => 'required',
            'zona_version2_id' => 'required',
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
            'tipo_id_pisi_id.required' => 'El campo es obligatorio',
            'document.required' => 'El campo es obligatorio',
            'rips_tipo_usuario_version2_id.required' => 'El campo es obligatorio',
            'birth_date.required' => 'El campo es obligatorio',
            'birth_date.date' => 'El campo debe ser una fecha',
            'sexo_id.required' => 'El campo es obligatorio',
            'pais_residency_id.required' => 'El campo es obligatorio',
            'zona_version2_id.required' => 'El campo es obligatorio',
            'incapacity.required' => 'El campo es obligatorio',
            'pais_origin_id.required' => 'El campo es obligatorio',
            'first_name.required' => 'El campo es obligatorio',
            'first_surname.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tipo_id_pisi_id' => getValueSelectInfinite($this->tipo_id_pisi_id),
            'rips_tipo_usuario_version2_id' => getValueSelectInfinite($this->rips_tipo_usuario_version2_id),
            'sexo_id' => getValueSelectInfinite($this->sexo_id),
            'pais_residency_id' => getValueSelectInfinite($this->pais_residency_id),
            'municipio_residency_id' => getValueSelectInfinite($this->municipio_residency_id),
            'zona_version2_id' => getValueSelectInfinite($this->zona_version2_id),
            'incapacity' => $this->incapacity === 'Si' ? 1 : 0,
            'pais_origin_id' => getValueSelectInfinite($this->pais_origin_id),
        ]);
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
