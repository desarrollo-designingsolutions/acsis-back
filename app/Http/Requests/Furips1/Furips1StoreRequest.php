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
            'invoice_id' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'invoice_id.required' => 'El campo es obligatorio',

        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('driverDocumentType')) {
            $merge['driverDocumentType'] = getValueSelectInfinite($this->driverDocumentType);
        }
        if ($this->has('driverResidenceDepartmentCode')) {
            $merge['driverResidenceDepartmentCode'] = getValueSelectInfinite($this->driverResidenceDepartmentCode);
        }
        if ($this->has('driverResidenceMunicipalityCode')) {
            $merge['driverResidenceMunicipalityCode'] = getValueSelectInfinite($this->driverResidenceMunicipalityCode);
        }
        if ($this->has('eventDepartmentCode')) {
            $merge['eventDepartmentCode'] = getValueSelectInfinite($this->eventDepartmentCode);
        }
        if ($this->has('eventMunicipalityCode')) {
            $merge['eventMunicipalityCode'] = getValueSelectInfinite($this->eventMunicipalityCode);
        }
        if ($this->has('ownerDocumentType')) {
            $merge['ownerDocumentType'] = getValueSelectInfinite($this->ownerDocumentType);
        }
        if ($this->has('ownerResidenceDepartmentCode')) {
            $merge['ownerResidenceDepartmentCode'] = getValueSelectInfinite($this->ownerResidenceDepartmentCode);
        }
        if ($this->has('ownerResidenceMunicipalityCode')) {
            $merge['ownerResidenceMunicipalityCode'] = getValueSelectInfinite($this->ownerResidenceMunicipalityCode);
        }
        if ($this->has('receivingHealthProviderCode')) {
            $merge['receivingHealthProviderCode'] = getValueSelectInfinite($this->receivingHealthProviderCode);
        }
        if ($this->has('referringHealthProviderCode')) {
            $merge['referringHealthProviderCode'] = getValueSelectInfinite($this->referringHealthProviderCode);
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
