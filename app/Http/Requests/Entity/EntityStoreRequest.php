<?php

namespace App\Http\Requests\Entity;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class EntityStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'corporate_name' => 'required',
            'nit' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'type_entity_id' => 'required',
        ];

        if (! empty($this->email) || $this->email != 'null' || $this->email != null) {
            $rules['email'] = 'required|unique:entities,email,'.$this->id.',id';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'corporate_name.required' => 'El campo es obligatorio',
            'nit.required' => 'El campo es obligatorio',
            'phone.required' => 'El campo es obligatorio',
            'address.required' => 'El campo es obligatorio',
            'email.unique' => 'El Email ya existe',
            'email.email' => 'El campo debe contener un correo valido',
            'type_entity_id.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([]);
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
