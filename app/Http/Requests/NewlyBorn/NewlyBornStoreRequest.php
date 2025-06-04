<?php

namespace App\Http\Requests\NewlyBorn;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class NewlyBornStoreRequest extends FormRequest
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

            'fechaNacimiento' => 'required',
            'edadGestacional' => 'required',
            'numConsultasCPrenatal' => 'required',
            'codSexoBiologico_id' => 'required',
            'peso' => 'required',
            'codDiagnosticoPrincipal_id' => 'required',
            'condicionDestinoUsuarioEgreso_id' => 'required',
            'fechaEgreso' => 'required',
            'tipoDocumentoIdentificacion_id' => 'required',
            'numDocumentoIdentificacion' => 'required',

        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'invoice_id.required' => 'El campo es obligatorio',

            'fechaNacimiento.required' => 'El campo es obligatorio',
            'edadGestacional.required' => 'El campo es obligatorio',
            'numConsultasCPrenatal.required' => 'El campo es obligatorio',
            'codSexoBiologico_id.required' => 'El campo es obligatorio',
            'peso.required' => 'El campo es obligatorio',
            'codDiagnosticoPrincipal_id.required' => 'El campo es obligatorio',
            'condicionDestinoUsuarioEgreso_id.required' => 'El campo es obligatorio',
            'fechaEgreso.required' => 'El campo es obligatorio',
            'tipoDocumentoIdentificacion_id.required' => 'El campo es obligatorio',
            'numDocumentoIdentificacion.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('codSexoBiologico_id')) {
            $merge['codSexoBiologico_id'] = getValueSelectInfinite($this->codSexoBiologico_id);
        }

        if ($this->has('codDiagnosticoPrincipal_id')) {
            $merge['codDiagnosticoPrincipal_id'] = getValueSelectInfinite($this->codDiagnosticoPrincipal_id);
        }

        if ($this->has('condicionDestinoUsuarioEgreso_id')) {
            $merge['condicionDestinoUsuarioEgreso_id'] = getValueSelectInfinite($this->condicionDestinoUsuarioEgreso_id);
        }

        if ($this->has('codDiagnosticoCausaMuerte_id')) {
            $merge['codDiagnosticoCausaMuerte_id'] = getValueSelectInfinite($this->codDiagnosticoCausaMuerte_id);
        }

        if ($this->has('tipoDocumentoIdentificacion_id')) {
            $merge['tipoDocumentoIdentificacion_id'] = getValueSelectInfinite($this->tipoDocumentoIdentificacion_id);
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
