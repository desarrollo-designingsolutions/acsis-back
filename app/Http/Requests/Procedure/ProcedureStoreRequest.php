<?php

namespace App\Http\Requests\Procedure;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProcedureStoreRequest extends FormRequest
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
            'fechaInicioAtencion' => 'required',
            'codProcedimiento_id' => 'required',
            'viaIngresoServicioSalud_id' => 'required',
            'modalidadGrupoServicioTecSal_id' => 'required',
            'grupoServicios_id' => 'required',
            'codServicio_id' => 'required',
            'finalidadTecnologiaSalud_id' => 'required',
            'codDiagnosticoPrincipal_id' => 'required',
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
            'fechaInicioAtencion.required' => 'El campo es obligatorio',
            'codProcedimiento_id.required' => 'El campo es obligatorio',
            'viaIngresoServicioSalud_id.required' => 'El campo es obligatorio',
            'modalidadGrupoServicioTecSal_id.required' => 'El campo es obligatorio',
            'grupoServicios_id.required' => 'El campo es obligatorio',
            'codServicio_id.required' => 'El campo es obligatorio',
            'finalidadTecnologiaSalud_id.required' => 'El campo es obligatorio',
            'codDiagnosticoPrincipal_id.required' => 'El campo es obligatorio',
            'valorPagoModerador.required' => 'El campo es obligatorio',
            'vrServicio.required' => 'El campo es obligatorio',
            'tipoDocumentoIdentificacion_id.required' => 'El campo es obligatorio',
            'numDocumentoIdentificacion.required' => 'El campo es obligatorio',
            'conceptoRecaudo_id.required' => 'El campo es obligatorio',
            'numFEVPagoModerador.required' => 'El campo es obligatorio',

        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('codProcedimiento_id')) {
            $merge['codProcedimiento_id'] = getValueSelectInfinite($this->codProcedimiento_id);
        }

        if ($this->has('viaIngresoServicioSalud_id')) {
            $merge['viaIngresoServicioSalud_id'] = getValueSelectInfinite($this->viaIngresoServicioSalud_id);
        }

        if ($this->has('modalidadGrupoServicioTecSal_id')) {
            $merge['modalidadGrupoServicioTecSal_id'] = getValueSelectInfinite($this->modalidadGrupoServicioTecSal_id);
        }

        if ($this->has('grupoServicios_id')) {
            $merge['grupoServicios_id'] = getValueSelectInfinite($this->grupoServicios_id);
        }

        if ($this->has('codServicio_id')) {
            $merge['codServicio_id'] = getValueSelectInfinite($this->codServicio_id);
        }

        if ($this->has('finalidadTecnologiaSalud_id')) {
            $merge['finalidadTecnologiaSalud_id'] = getValueSelectInfinite($this->finalidadTecnologiaSalud_id);
        }

        if ($this->has('codDiagnosticoPrincipal_id')) {
            $merge['codDiagnosticoPrincipal_id'] = getValueSelectInfinite($this->codDiagnosticoPrincipal_id);
        }

        if ($this->has('codDiagnosticoRelacionado_id')) {
            $merge['codDiagnosticoRelacionado_id'] = getValueSelectInfinite($this->codDiagnosticoRelacionado_id);
        }

        if ($this->has('codComplicacion_id')) {
            $merge['codComplicacion_id'] = getValueSelectInfinite($this->codComplicacion_id);
        }

        if ($this->has('conceptoRecaudo_id')) {
            $merge['conceptoRecaudo_id'] = getValueSelectInfinite($this->conceptoRecaudo_id);
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
