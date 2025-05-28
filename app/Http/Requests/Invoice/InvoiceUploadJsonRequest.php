<?php

namespace App\Http\Requests\Invoice;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvoiceUploadJsonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|uuid|exists:companies,id',
            'archiveJson' => 'required|file|mimes:json|max:30000', // 30MB = 30000 KB
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'company_id.required' => 'El ID de la empresa es obligatorio.',
            'company_id.uuid' => 'El ID de la empresa debe ser un UUID válido.',
            'company_id.exists' => 'El ID de la empresa no existe en la base de datos.',
            'archiveJson.required' => 'El archivo JSON es obligatorio.',
            'archiveJson.file' => 'El archivo proporcionado no es válido.',
            'archiveJson.mimes' => 'El archivo debe tener la extensión .json.',
            'archiveJson.max' => 'El archivo no debe exceder los 30MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([]);
    }

    /**
     * Handle a failed validation attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'code' => 422,
            'message' => Constants::ERROR_MESSAGE_VALIDATION_BACK,
            'errors' => $validator->errors(),
        ], 422));
    }
}
