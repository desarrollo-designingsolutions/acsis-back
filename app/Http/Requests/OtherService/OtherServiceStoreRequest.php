<?php

namespace App\Http\Requests\OtherService;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OtherServiceStoreRequest extends FormRequest
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
            'fechaSuministroTecnologia' => 'required',
            'codTecnologiaSalud' => 'required',
            'cantidadOS' => 'required',
            'vrUnitOS' => 'required',
            'valorPagoModerador' => 'required',
            'vrServicio' => 'required',
            'tipoDocumentoIdentificacion_id' => 'required',
            'numDocumentoIdentificacion' => 'required',
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
            'numAutorizacion.required' => 'El campo es obligatorio',
            'fechaSuministroTecnologia.required' => 'El campo es obligatorio',
            'codTecnologiaSalud.required' => 'El campo es obligatorio',
            'cantidadOS.required' => 'El campo es obligatorio',
            'vrUnitOS.required' => 'El campo es obligatorio',
            'valorPagoModerador.required' => 'El campo es obligatorio',
            'vrServicio.required' => 'El campo es obligatorio',
            'tipoDocumentoIdentificacion_id.required' => 'El campo es obligatorio',
            'numDocumentoIdentificacion.required' => 'El campo es obligatorio',
            'numFEVPagoModerador.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('tipoOS_id')) {
            $merge['tipoOS_id'] = getValueSelectInfinite($this->tipoOS_id);
        }
        if ($this->has('conceptoRecaudo_id')) {
            $merge['conceptoRecaudo_id'] = getValueSelectInfinite($this->conceptoRecaudo_id);
        }
        if ($this->has('codTecnologiaSalud')) {
            $merge['codTecnologiaSalud'] = getValueSelectInfinite($this->codTecnologiaSalud, 'codigo');
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
