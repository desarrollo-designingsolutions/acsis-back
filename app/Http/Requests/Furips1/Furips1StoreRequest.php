<?php

namespace App\Http\Requests\Furips1;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class Furips1StoreRequest extends FormRequest
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

        $rules = [
            'user_id' => 'required',
            'company_id' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'El campo es obligatorio',
            'company_id.required' => 'El campo es obligatorio',

        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];
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
