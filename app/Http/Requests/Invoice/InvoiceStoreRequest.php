<?php

namespace App\Http\Requests\Invoice;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvoiceStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'serviceVendor' => 'required',
            'entity' => 'required',
            // 'patient' => 'required',
            'invoice_number' => 'required',
            'invoice_date' => 'required',
            'radication_date' => 'required',
            'value_approved' => 'required',
            'value_glosa' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'serviceVendor.required' => 'El campo es obligatorio',
            'entity.required' => 'El campo es obligatorio',
            'patient.required' => 'El campo es obligatorio',
            'invoice_number.required' => 'El campo es obligatorio',
            'invoice_date.required' => 'El campo es obligatorio',
            'radication_date.required' => 'El campo es obligatorio',
            'value_approved.required' => 'El campo es obligatorio',
            'value_glosa.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'service_vendor_id' => $this->input('serviceVendor.value'),
            'entity_id' => $this->input('entity.value'),
            'patient_id' => '1',
        ]);
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
