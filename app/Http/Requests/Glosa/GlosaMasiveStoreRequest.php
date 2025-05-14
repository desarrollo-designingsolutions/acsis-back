<?php

namespace App\Http\Requests\Glosa;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GlosaMasiveStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'servicesIds' => 'required|array|min:1',
            'glosas' => 'required|array|min:1',

            'glosas.*.user_id' => 'required',
            'glosas.*.code_glosa_id' => 'required',
            'glosas.*.observation' => 'required',

            'glosas.*.partialValue' => 'required|numeric|gt:0|lte:100',
            'glosas.*.date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'servicesIds.required' => ' El campo glosas es obligatorio',
            'servicesIds.array' => 'El campo glosas debe ser un arreglo',
            'servicesIds.min' => 'Debe enviar al menos una glosa',

            'glosas.required' => 'Debe enviar al menos una glosa',
            'glosas.array' => 'El campo glosas debe ser un arreglo',

            'glosas.*.user_id.required' => 'El usuario es obligatorio',
            'glosas.*.code_glosa_id.required' => 'El código de glosa es obligatorio',
            'glosas.*.observation.required' => 'La observación es obligatoria',

            'glosas.*.partialValue.required' => 'El valor glosa es obligatorio',
            'glosas.*.partialValue.numeric' => 'El valor glosa debe ser numérico',
            'glosas.*.partialValue.gt' => 'El valor glosa debe ser mayor a cero',
            'glosas.*.partialValue.lte' => 'El valor glosa no puede ser mayor a 100',
            'glosas.*.date.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
        if ($this->has('glosas') && is_string($this->glosas)) {
            $glosas = array_map(function ($glosa) {
                return [
                    ...$glosa,
                    'code_glosa_id' => isset($glosa['codeGlosa']['value']) ? $glosa['codeGlosa']['value'] : ($glosa['code_glosa_id'] ?? null),
                    'partialValue' => is_string($glosa['partialValue']) ? str_replace(',', '.', $glosa['partialValue']) : $glosa['partialValue'],
                ];
            }, json_decode($this->glosas, 1));

            $merge['glosas'] = $glosas;
        }
        if ($this->has('servicesIds') && is_string($this->servicesIds)) {
            $servicesIds = json_decode($this->servicesIds, 1);

            $merge['servicesIds'] = $servicesIds;
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
