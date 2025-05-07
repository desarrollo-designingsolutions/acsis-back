<?php

namespace App\Http\Requests\Invoice;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvoiceType002StoreRequest extends FormRequest
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
            'tipo_nota_id' => 'required',
            'invoice_number' => 'required',
            'note_number' => 'required',
            'radication_number' => 'required',
            'invoice_date' => 'required',
            'type' => 'required',
            'radication_date' => 'required',
            'value_paid' => 'required',
            'value_glosa' => 'required',
            'total' => 'required',
            'soat' => 'required',
            'soat.policy_number' => 'required',
            'soat.accident_date' => 'required|date',
            'soat.start_date' => 'required|date',
            'soat.end_date' => 'required|date',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'service_vendor_id.required' => 'El campo es obligatorio',
            'entity_id.required' => 'El campo es obligatorio',
            'patient_id.required' => 'El campo es obligatorio',
            'tipo_nota_id.required' => 'El campo es obligatorio',
            'invoice_number.required' => 'El campo es obligatorio',
            'note_number.required' => 'El campo es obligatorio',
            'radication_number.required' => 'El campo es obligatorio',
            'invoice_date.required' => 'El campo es obligatorio',
            'type.required' => 'El campo es obligatorio',
            'radication_date.required' => 'El campo es obligatorio',
            'value_paid.required' => 'El campo es obligatorio',
            'total.required' => 'El campo es obligatorio',
            'value_glosa.required' => 'El campo es obligatorio',
            'soat.required' => 'El campo es obligatorio',
            'soat.policy_number.required' => 'El campo es obligatorio',
            'soat.accident_date.required' => 'El campo es obligatorio',
            'soat.accident_date.date' => 'El campo debe ser una fecha',
            'soat.start_date.required' => 'El campo es obligatorio',
            'soat.start_date.date' => 'El campo debe ser una fecha',
            'soat.end_date.required' => 'El campo es obligatorio',
            'soat.end_date.date' => 'El campo debe ser una fecha',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'service_vendor_id' => is_array($this->serviceVendor) && isset($this->serviceVendor['value']) ? $this->serviceVendor['value'] : $this->serviceVendor,
            'entity_id' => is_array($this->entity) && isset($this->entity['value']) ? $this->entity['value'] : $this->entity,
            'patient_id' => is_array($this->patient) && isset($this->patient['value']) ? $this->patient['value'] : $this->patient,
            'tipo_nota_id' => is_array($this->TipoNota) && isset($this->TipoNota['value']) ? $this->TipoNota['value'] : $this->TipoNota,
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
