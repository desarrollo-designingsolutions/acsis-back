<?php

namespace App\Http\Requests\Service;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ServiceStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'cups_rip_id' => 'required',
            'quantity' => 'required',
            'unit_value' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'cups_rip_id.required' => 'El campo es obligatorio',
            'quantity.required' => 'El campo es obligatorio',
            'unit_value.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('cups_rip_id')) {
            $merge['cups_rip_id'] = is_array($this->cups_rip_id) ? $this->cups_rip_id['value'] : $this->cups_rip_id;
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
