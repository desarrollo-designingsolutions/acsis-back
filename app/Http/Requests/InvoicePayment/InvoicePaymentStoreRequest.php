<?php

namespace App\Http\Requests\InvoicePayment;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvoicePaymentStoreRequest extends FormRequest
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
            'invoice_id' => 'required',
            'value_paid' => 'required',
            'date_payment' => 'required',
            'observations' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'invoice_id.required' => 'El campo es obligatorio',
            'value_paid.required' => 'El campo es obligatorio',
            'date_payment.required' => 'El campo es obligatorio',
            'observations.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'invoice_id' => formattedElement($this->invoice_id),
            'value_paid' => formattedElement($this->value_paid),
            'date_payment' => formattedElement($this->date_payment),
            'observations' => formattedElement($this->observations),
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
