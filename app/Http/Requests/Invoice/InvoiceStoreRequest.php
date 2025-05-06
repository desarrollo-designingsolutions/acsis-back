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
            'service_vendor_id' => 'required',
            'entity_id' => 'required',
            'patient_id' => 'required',
            'invoice_number' => 'required',
            'invoice_date' => 'required',
            'type' => 'required',
            'radication_date' => 'required',
            'value_approved' => 'required',
            'value_glosa' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'service_vendor_id.required' => 'El campo es obligatorio',
            'entity_id.required' => 'El campo es obligatorio',
            'patient_id.required' => 'El campo es obligatorio',
            'invoice_number.required' => 'El campo es obligatorio',
            'invoice_date.required' => 'El campo es obligatorio',
            'type.required' => 'El campo es obligatorio',
            'radication_date.required' => 'El campo es obligatorio',
            'value_approved.required' => 'El campo es obligatorio',
            'value_glosa.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'service_vendor_id' => is_array($this->serviceVendor) && isset($this->serviceVendor['value']) ? $this->serviceVendor['value'] : $this->serviceVendor,
            'entity_id' => is_array($this->entity) && isset($this->entity['value']) ? $this->entity['value'] : $this->entity,
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
