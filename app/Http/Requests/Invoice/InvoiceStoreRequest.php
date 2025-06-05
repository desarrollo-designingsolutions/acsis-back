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
            'invoice_date' => 'required',
            'type' => 'required',
            'status' => 'required',
        ];

        // si no se selecciona el tipo de nota y no se ingresa el numero de nota entonces el numero de factura es obligatorio
        if (! $this->tipo_nota_id) {
            $rules2 = [
                'invoice_number' => 'required',
            ];
            $rules = array_merge($rules, $rules2);
        }

        // si se selecciona el tipo de nota entonces el numero de nota es obligatorio
        if ($this->note_number) {
            $rules2 = [
                'tipo_nota_id' => 'required',
            ];
            $rules = array_merge($rules, $rules2);
        }

        // si el tipo de nota se leecciona entonces el numero de nota es obligatorio
        if ($this->tipo_nota_id) {
            $rules2 = [
                'note_number' => 'required',
            ];
            $rules = array_merge($rules, $rules2);
        }

        if ($this->radication_number) {
            $rules2 = [
                'radication_date' => 'required',
            ];
            $rules = array_merge($rules, $rules2);
        }

        if ($this->radication_date) {
            $rules2 = [
                'radication_number' => 'required',
            ];
            $rules = array_merge($rules, $rules2);
        }

        if ($this->type == 'INVOICE_TYPE_002') {
            $rules3 = [
                'soat' => 'required',
                'soat.policy_number' => 'required',
                'soat.accident_date' => 'required|date',
                'soat.start_date' => 'required|date',
                'soat.end_date' => 'required|date',
                'soat.insurance_statuse_id' => 'required',
            ];
            $rules = array_merge($rules, $rules3);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'service_vendor_id.required' => 'El campo es obligatorio',
            'entity_id.required' => 'El campo es obligatorio',
            'patient_id.required' => 'El campo es obligatorio',
            'invoice_number.required' => 'El campo es obligatorio',
            'radication_number.required' => 'El campo es obligatorio',
            'invoice_date.required' => 'El campo es obligatorio',
            'type.required' => 'El campo es obligatorio',
            'radication_date.required' => 'El campo es obligatorio',
            'status.required' => 'El campo es obligatorio',

            'soat.required' => 'El campo es obligatorio',
            'soat.policy_number.required' => 'El campo es obligatorio',
            'soat.accident_date.required' => 'El campo es obligatorio',
            'soat.accident_date.date' => 'El campo debe ser una fecha',
            'soat.start_date.required' => 'El campo es obligatorio',
            'soat.start_date.date' => 'El campo debe ser una fecha',
            'soat.end_date.required' => 'El campo es obligatorio',
            'soat.end_date.date' => 'El campo debe ser una fecha',
            'soat.insurance_statuse_id.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('service_vendor_id')) {
            $merge['service_vendor_id'] = getValueSelectInfinite($this->service_vendor_id);
        }
        if ($this->has('entity_id')) {
            $merge['entity_id'] = getValueSelectInfinite($this->entity_id);
        }
        if ($this->has('patient_id')) {
            $merge['patient_id'] = getValueSelectInfinite($this->patient_id);
        }
        if ($this->has('tipo_nota_id')) {
            $merge['tipo_nota_id'] = getValueSelectInfinite($this->tipo_nota_id);
        }
        if ($this->has('soat.insurance_statuse_id')) {
            $merge['soat.insurance_statuse_id'] = getValueSelectInfinite($this->soat['insurance_statuse_id']);
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
