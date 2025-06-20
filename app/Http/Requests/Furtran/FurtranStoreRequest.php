<?php

namespace App\Http\Requests\Furtran;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FurtranStoreRequest extends FormRequest
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

        if ($this->has('claimantIdType_id')) {
            $merge['claimantIdType_id'] = getValueSelectInfinite($this->claimantIdType_id);
        }
        if ($this->has('claimantDepartmentCode_id')) {
            $merge['claimantDepartmentCode_id'] = getValueSelectInfinite($this->claimantDepartmentCode_id);
        }
        if ($this->has('claimantMunicipalityCode_id')) {
            $merge['claimantMunicipalityCode_id'] = getValueSelectInfinite($this->claimantMunicipalityCode_id);
        }
        if ($this->has('pickupDepartmentCode_id')) {
            $merge['pickupDepartmentCode_id'] = getValueSelectInfinite($this->pickupDepartmentCode_id);
        }
        if ($this->has('pickupMunicipalityCode_id')) {
            $merge['pickupMunicipalityCode_id'] = getValueSelectInfinite($this->pickupMunicipalityCode_id);
        }
        if ($this->has('transferPickupDepartmentCode_id')) {
            $merge['transferPickupDepartmentCode_id'] = getValueSelectInfinite($this->transferPickupDepartmentCode_id);
        }
        if ($this->has('transferPickupMunicipalityCode_id')) {
            $merge['transferPickupMunicipalityCode_id'] = getValueSelectInfinite($this->transferPickupMunicipalityCode_id);
        }
        if ($this->has('ipsReceptionHabilitationCode_id')) {
            $merge['ipsReceptionHabilitationCode_id'] = getValueSelectInfinite($this->ipsReceptionHabilitationCode_id);
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
