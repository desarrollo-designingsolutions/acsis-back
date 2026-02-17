<?php

namespace App\Http\Requests\Invoice;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule; // <--- 1. Importar Rule

class InvoiceStoreRequest extends FormRequest
{
    public function rules(): array
    {
        // Obtener el ID de la empresa del request
        $companyId =  $this->company_id;

        $rules = [
            'service_vendor_id' => 'required',
            'entity_id' => 'required',
            'patient_id' => 'required',
            'invoice_date' => 'required',
            'type' => 'required',
            'status' => 'required',
        ];

        // LOGICA PRINCIPAL AQUÍ:
        // Si no es nota (crédito/débito), validamos la factura
        if (! $this->tipo_nota_id) {
            $rules2 = [
                'invoice_number' => [
                    'required',
                    // Validar unicidad compuesta: invoice_number + company_id + service_vendor_id
                    Rule::unique('invoices', 'invoice_number')
                        ->where(function ($query) use ($companyId) {
                            return $query->where('company_id', $companyId)
                                         // Usamos $this->service_vendor_id porque prepareForValidation ya lo convirtió al ID
                                         ->where('service_vendor_id', $this->service_vendor_id)
                                         ->where('entity_id', $this->entity_id)
                                         ->whereNull('deleted_at'); // Asumiendo que usas SoftDeletes
                        })
                        ->ignore($this->id)
                ],
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

        // si el tipo de nota se selecciona entonces el numero de nota es obligatorio
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
            // Mensaje personalizado para el error de duplicado
            'invoice_number.unique' => 'Este número de factura ya existe para este prestador y entidad en esta empresa.',

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
        // ... (Tu código prepareForValidation se mantiene igual)
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
             // Nota: aquí corregí el acceso al array, asegúrate que $this->soat sea accesible así
             // Si $this->soat es un array, esto está bien si viene como input.
             if(isset($this->soat['insurance_statuse_id'])){
                 $merge['soat.insurance_statuse_id'] = getValueSelectInfinite($this->soat['insurance_statuse_id']);
             }
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
