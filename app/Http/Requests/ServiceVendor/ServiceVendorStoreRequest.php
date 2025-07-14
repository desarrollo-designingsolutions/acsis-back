<?php

namespace App\Http\Requests\ServiceVendor;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;


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
            'type_vendor_id' => 'required',
            'ipsable_id' => 'required',
            'ipsable_type' => 'required',
        ];

        // Validar email solo si no está vacío o no es null
        if (!empty($this->email) && $this->email !== 'null' && $this->email !== null) {
            $rules['email'] = [
                'required',
                'email', // Valida que sea un correo válido
                Rule::unique('service_vendors', 'email')->where(function ($query) {
                    return $query->where('company_id', $this->company_id)
                        ->where('id', '!=', $this->id); // Excluye el ID actual en caso de actualización
                }),
            ];
        }


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
            'type_vendor_id.required' => 'El campo es obligatorio',
            'ipsable_id.required' => 'El campo es obligatorio',
            'ipsable_type.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('ipsable_id')) {
            $merge['ipsable_id'] = getValueSelectInfinite($this->ipsable_id);
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
