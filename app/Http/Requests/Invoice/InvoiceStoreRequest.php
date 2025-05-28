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
            'radication_number' => 'required',
            'invoice_date' => 'required',
            'type' => 'required',
            'radication_date' => 'required',
            'status' => 'required',
        ];

        if (! $this->tipo_nota_id && ! $this->note_number) {
            $rules2 = [
                'invoice_number' => 'required',
            ];
            $rules = array_merge($rules, $rules2);
        }

        if ($this->type == 'INVOICE_TYPE_002') {
            $rules3 = [
                'policy' => 'required',
                'policy.policy_number' => 'required',
                'policy.accident_date' => 'required|date',
                'policy.start_date' => 'required|date',
                'policy.end_date' => 'required|date',
                'policy.insurance_statuse_id' => 'required',
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

            'policy.required' => 'El campo es obligatorio',
            'policy.policy_number.required' => 'El campo es obligatorio',
            'policy.accident_date.required' => 'El campo es obligatorio',
            'policy.accident_date.date' => 'El campo debe ser una fecha',
            'policy.start_date.required' => 'El campo es obligatorio',
            'policy.start_date.date' => 'El campo debe ser una fecha',
            'policy.end_date.required' => 'El campo es obligatorio',
            'policy.end_date.date' => 'El campo debe ser una fecha',
            'policy.insurance_statuse_id.required' => 'El campo es obligatorio',
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
        if ($this->has('policy.insurance_statuse_id')) {
            $merge['policy.insurance_statuse_id'] = getValueSelectInfinite($this->policy['insurance_statuse_id']);
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
