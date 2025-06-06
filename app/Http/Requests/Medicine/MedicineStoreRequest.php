<?php

namespace App\Http\Requests\Medicine;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MedicineStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'invoice_id' => 'required',

            'fechaDispensAdmon' => 'required',
            'codDiagnosticoPrincipal_id' => 'required',
            'tipoMedicamento_id' => 'required',
            'nomTecnologiaSalud_id' => 'required',
            'concentracionMedicamento' => 'required',
            'unidadMedida_id' => 'required',
            'formaFarmaceutica_id' => 'required',
            'unidadMinDispensa_id' => 'required',
            'cantidadMedicamento' => 'required',
            'diasTratamiento' => 'required',
            'vrUnitMedicamento' => 'required',
            'vrServicio' => 'required',
            'tipoDocumentoIdentificacion_id' => 'required',
            'numDocumentoIdentificacion' => 'required',

            'codTecnologiaSaludable_id' => 'required',
            'codTecnologiaSaludable_type' => 'required',

        ];

        if ($this->valorPagoModerador > 0) {
            $rules['conceptoRecaudo_id'] = 'required';
            $rules['numFEVPagoModerador'] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'invoice_id.required' => 'El campo es obligatorio',

            'fechaDispensAdmon' => 'El campo es obligatorio',
            'codDiagnosticoPrincipal_id' => 'El campo es obligatorio',
            'tipoMedicamento_id' => 'El campo es obligatorio',
            'codTecnologiaSaludable_id' => 'El campo es obligatorio',
            'codTecnologiaSaludable_type' => 'El campo es obligatorio',
            'nomTecnologiaSalud_id' => 'El campo es obligatorio',
            'concentracionMedicamento' => 'El campo es obligatorio',
            'unidadMedida_id' => 'El campo es obligatorio',
            'formaFarmaceutica_id' => 'El campo es obligatorio',
            'unidadMinDispensa_id' => 'El campo es obligatorio',
            'cantidadMedicamento' => 'El campo es obligatorio',
            'diasTratamiento' => 'El campo es obligatorio',
            'vrUnitMedicamento' => 'El campo es obligatorio',
            'vrServicio' => 'El campo es obligatorio',
            'conceptoRecaudo_id' => 'El campo es obligatorio',
            'tipoDocumentoIdentificacion_id.required' => 'El campo es obligatorio',
            'numDocumentoIdentificacion.required' => 'El campo es obligatorio',
            'numFEVPagoModerador.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('codDiagnosticoPrincipal_id')) {
            $merge['codDiagnosticoPrincipal_id'] = getValueSelectInfinite($this->codDiagnosticoPrincipal_id);
        }

        if ($this->has('codDiagnosticoRelacionado_id')) {
            $merge['codDiagnosticoRelacionado_id'] = getValueSelectInfinite($this->codDiagnosticoRelacionado_id);
        }

        if ($this->has('tipoMedicamento_id')) {
            $merge['tipoMedicamento_id'] = getValueSelectInfinite($this->tipoMedicamento_id);
        }

        if ($this->has('unidadMedida_id')) {
            $merge['unidadMedida_id'] = getValueSelectInfinite($this->unidadMedida_id);
        }

        if ($this->has('conceptoRecaudo_id')) {
            $merge['conceptoRecaudo_id'] = getValueSelectInfinite($this->conceptoRecaudo_id);
        }
        if ($this->has('tipoDocumentoIdentificacion_id')) {
            $merge['tipoDocumentoIdentificacion_id'] = getValueSelectInfinite($this->tipoDocumentoIdentificacion_id);
        }
        if ($this->has('unidadMinDispensa_id')) {
            $merge['unidadMinDispensa_id'] = getValueSelectInfinite($this->unidadMinDispensa_id);
        }
        if ($this->has('formaFarmaceutica_id')) {
            $merge['formaFarmaceutica_id'] = getValueSelectInfinite($this->formaFarmaceutica_id);
        }
        if ($this->has('nomTecnologiaSalud_id')) {
            $merge['nomTecnologiaSalud_id'] = getValueSelectInfinite($this->nomTecnologiaSalud_id);
        }
        if ($this->has('codTecnologiaSaludable_id')) {
            $merge['codTecnologiaSaludable_id'] = getValueSelectInfinite($this->codTecnologiaSaludable_id);
        }

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
