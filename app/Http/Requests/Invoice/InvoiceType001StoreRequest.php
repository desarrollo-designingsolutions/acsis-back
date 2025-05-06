<?php

namespace App\Http\Requests\Invoice;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvoiceType001StoreRequest extends FormRequest
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
            'invoice_number' => 'required',
            'radication_number' => 'required',
            'invoice_date' => 'required',
            'type' => 'required',
            'radication_date' => 'required',
            'value_approved' => 'required',
            'value_glosa' => 'required',
            'total' => 'required',
            'type_document_id' => 'required',
            'document' => 'required',
            'first_name' => 'required',
            'second_name' => 'required',
            'first_surname' => 'required',
            'second_surname' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'service_vendor_id.required' => 'El campo es obligatorio',
            'entity_id.required' => 'El campo es obligatorio',
            'invoice_number.required' => 'El campo es obligatorio',
            'radication_number.required' => 'El campo es obligatorio',
            'invoice_date.required' => 'El campo es obligatorio',
            'type.required' => 'El campo es obligatorio',
            'radication_date.required' => 'El campo es obligatorio',
            'value_approved.required' => 'El campo es obligatorio',
            'value_glosa.required' => 'El campo es obligatorio',
            'total.required' => 'El campo es obligatorio',
            'type_document_id.required' => 'El campo es obligatorio',
            'document.required' => 'El campo es obligatorio',
            'first_name.required' => 'El campo es obligatorio',
            'second_name.required' => 'El campo es obligatorio',
            'first_surname.required' => 'El campo es obligatorio',
            'second_surname.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'service_vendor_id' => is_array($this->serviceVendor) && isset($this->serviceVendor['value']) ? $this->serviceVendor['value'] : $this->serviceVendor,
            'entity_id' => is_array($this->entity) && isset($this->entity['value']) ? $this->entity['value'] : $this->entity,
            'type_document_id' => is_array($this->typeDocument) && isset($this->typeDocument['value']) ? $this->typeDocument['value'] : $this->typeDocument,
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
