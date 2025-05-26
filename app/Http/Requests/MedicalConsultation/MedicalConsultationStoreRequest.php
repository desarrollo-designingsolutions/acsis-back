<?php

namespace App\Http\Requests\MedicalConsultation;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MedicalConsultationStoreRequest extends FormRequest
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
            'codConsulta_id' => 'required',
            'modalidadGrupoServicioTecSal_id' => 'required',
            'grupoServicios_id' => 'required',
            'codServicio_id' => 'required',
            'finalidadTecnologiaSalud_id' => 'required',
            'causaMotivoAtencion_id' => 'required',
            'codDiagnosticoPrincipal_id' => 'required',
            'tipoDiagnosticoPrincipal_id' => 'required',
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
            'codConsulta_id.required' => 'El campo es obligatorio',
            'modalidadGrupoServicioTecSal_id.required' => 'El campo es obligatorio',
            'grupoServicios_id.required' => 'El campo es obligatorio',
            'codServicio_id.required' => 'El campo es obligatorio',
            'finalidadTecnologiaSalud_id.required' => 'El campo es obligatorio',
            'causaMotivoAtencion_id.required' => 'El campo es obligatorio',
            'codDiagnosticoPrincipal_id.required' => 'El campo es obligatorio',
            'tipoDiagnosticoPrincipal_id.required' => 'El campo es obligatorio',
            'vrServicio.required' => 'El campo es obligatorio',
            'conceptoRecaudo_id.required' => 'El campo es obligatorio',
            'tipoDocumentoIdentificacion_id.required' => 'El campo es obligatorio',
            'numDocumentoIdentificacion.required' => 'El campo es obligatorio',
            'numFEVPagoModerador.required' => 'El campo es obligatorio',

        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('codConsulta_id')) {
            $merge['codConsulta_id'] = getValueSelectInfinite($this->codConsulta_id);
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
        if ($this->has('causaMotivoAtencion_id')) {
            $merge['causaMotivoAtencion_id'] = getValueSelectInfinite($this->causaMotivoAtencion_id);
        }
        if ($this->has('codDiagnosticoPrincipal_id')) {
            $merge['codDiagnosticoPrincipal_id'] = getValueSelectInfinite($this->codDiagnosticoPrincipal_id);
        }
        if ($this->has('codDiagnosticoRelacionado1_id')) {
            $merge['codDiagnosticoRelacionado1_id'] = getValueSelectInfinite($this->codDiagnosticoRelacionado1_id);
        }
        if ($this->has('codDiagnosticoRelacionado2_id')) {
            $merge['codDiagnosticoRelacionado2_id'] = getValueSelectInfinite($this->codDiagnosticoRelacionado2_id);
        }
        if ($this->has('codDiagnosticoRelacionado3_id')) {
            $merge['codDiagnosticoRelacionado3_id'] = getValueSelectInfinite($this->codDiagnosticoRelacionado3_id);
        }
        if ($this->has('tipoDiagnosticoPrincipal_id')) {
            $merge['tipoDiagnosticoPrincipal_id'] = getValueSelectInfinite($this->tipoDiagnosticoPrincipal_id);
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
