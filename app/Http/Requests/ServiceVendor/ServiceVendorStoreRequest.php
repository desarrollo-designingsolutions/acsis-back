<?php

namespace App\Http\Requests\ServiceVendor;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceVendorStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required',
            'nit' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:companies,email,'.$this->id.',id',
            'ips_cod_habilitacion_id' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El campo es obligatorio',
            'nit.required' => 'El campo es obligatorio',
            'phone.required' => 'El campo es obligatorio',
            'address.required' => 'El campo es obligatorio',
            'email.unique' => 'El Email ya existe',
            'email.email' => 'El campo debe contener un correo valido',
            'ips_cod_habilitacion_id.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('ips_cod_habilitacion_id')) {
            $merge['ips_cod_habilitacion_id'] = getValueSelectInfinite($this->ips_cod_habilitacion_id);
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
