<?php

namespace App\Http\Requests\Hospitalization;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class HospitalizationStoreRequest extends FormRequest
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

            'viaIngresoServicioSalud_id' => 'required',
            'fechaInicioAtencion' => 'required',
            'causaMotivoAtencion_id' => 'required',
            'codDiagnosticoPrincipal_id' => 'required',
            'condicionDestinoUsuarioEgreso_id' => 'required',
            'codDiagnosticoPrincipalE_id' => 'required',
            'fechaEgreso' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'invoice_id.required' => 'El campo es obligatorio',

            'viaIngresoServicioSalud_id.required' => 'El campo es obligatorio',
            'fechaInicioAtencion.required' => 'El campo es obligatorio',
            'causaMotivoAtencion_id.required' => 'El campo es obligatorio',
            'codDiagnosticoPrincipal_id.required' => 'El campo es obligatorio',
            'condicionDestinoUsuarioEgreso_id.required' => 'El campo es obligatorio',
            'codDiagnosticoPrincipalE_id.required' => 'El campo es obligatorio',
            'fechaEgreso.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('viaIngresoServicioSalud_id')) {
            $merge['viaIngresoServicioSalud_id'] = getValueSelectInfinite($this->viaIngresoServicioSalud_id);
        }
        if ($this->has('causaMotivoAtencion_id')) {
            $merge['causaMotivoAtencion_id'] = getValueSelectInfinite($this->causaMotivoAtencion_id);
        }
        if ($this->has('codDiagnosticoPrincipal_id')) {
            $merge['codDiagnosticoPrincipal_id'] = getValueSelectInfinite($this->codDiagnosticoPrincipal_id);
        }
        if ($this->has('codDiagnosticoPrincipalE_id')) {
            $merge['codDiagnosticoPrincipalE_id'] = getValueSelectInfinite($this->codDiagnosticoPrincipalE_id);
        }
        if ($this->has('codDiagnosticoRelacionadoE1_id')) {
            $merge['codDiagnosticoRelacionadoE1_id'] = getValueSelectInfinite($this->codDiagnosticoRelacionadoE1_id);
        }
        if ($this->has('codDiagnosticoRelacionadoE2_id')) {
            $merge['codDiagnosticoRelacionadoE2_id'] = getValueSelectInfinite($this->codDiagnosticoRelacionadoE2_id);
        }
        if ($this->has('codDiagnosticoRelacionadoE3_id')) {
            $merge['codDiagnosticoRelacionadoE3_id'] = getValueSelectInfinite($this->codDiagnosticoRelacionadoE3_id);
        }
        if ($this->has('codComplicacion_id')) {
            $merge['codComplicacion_id'] = getValueSelectInfinite($this->codComplicacion_id);
        }
        if ($this->has('condicionDestinoUsuarioEgreso_id')) {
            $merge['condicionDestinoUsuarioEgreso_id'] = getValueSelectInfinite($this->condicionDestinoUsuarioEgreso_id);
        }
        if ($this->has('codDiagnosticoCausaMuerte_id')) {
            $merge['codDiagnosticoCausaMuerte_id'] = getValueSelectInfinite($this->codDiagnosticoCausaMuerte_id);
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
