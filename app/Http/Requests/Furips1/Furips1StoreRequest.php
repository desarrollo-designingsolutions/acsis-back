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

        if ($this->has('driverDocumentType_id')) {
            $merge['driverDocumentType_id'] = getValueSelectInfinite($this->driverDocumentType_id);
        }
        if ($this->has('driverResidenceDepartmentCode_id')) {
            $merge['driverResidenceDepartmentCode_id'] = getValueSelectInfinite($this->driverResidenceDepartmentCode_id);
        }
        if ($this->has('driverResidenceMunicipalityCode_id')) {
            $merge['driverResidenceMunicipalityCode_id'] = getValueSelectInfinite($this->driverResidenceMunicipalityCode_id);
        }
        if ($this->has('eventDepartmentCode_id')) {
            $merge['eventDepartmentCode_id'] = getValueSelectInfinite($this->eventDepartmentCode_id);
        }
        if ($this->has('eventMunicipalityCode_id')) {
            $merge['eventMunicipalityCode_id'] = getValueSelectInfinite($this->eventMunicipalityCode_id);
        }
        if ($this->has('ownerDocumentType_id')) {
            $merge['ownerDocumentType_id'] = getValueSelectInfinite($this->ownerDocumentType_id);
        }
        if ($this->has('ownerResidenceDepartmentCode_id')) {
            $merge['ownerResidenceDepartmentCode_id'] = getValueSelectInfinite($this->ownerResidenceDepartmentCode_id);
        }
        if ($this->has('ownerResidenceMunicipalityCode_id')) {
            $merge['ownerResidenceMunicipalityCode_id'] = getValueSelectInfinite($this->ownerResidenceMunicipalityCode_id);
        }
        if ($this->has('receivingHealthProviderCode_id')) {
            $merge['receivingHealthProviderCode_id'] = getValueSelectInfinite($this->receivingHealthProviderCode_id);
        }
        if ($this->has('referringHealthProviderCode_id')) {
            $merge['referringHealthProviderCode_id'] = getValueSelectInfinite($this->referringHealthProviderCode_id);
        }
        if ($this->has('primaryAdmissionDiagnosisCode_id')) {
            $merge['primaryAdmissionDiagnosisCode_id'] = getValueSelectInfinite($this->primaryAdmissionDiagnosisCode_id);
        }
        if ($this->has('associatedAdmissionDiagnosisCode1_id')) {
            $merge['associatedAdmissionDiagnosisCode1_id'] = getValueSelectInfinite($this->associatedAdmissionDiagnosisCode1_id);
        }
        if ($this->has('associatedAdmissionDiagnosisCode2_id')) {
            $merge['associatedAdmissionDiagnosisCode2_id'] = getValueSelectInfinite($this->associatedAdmissionDiagnosisCode2_id);
        }
        if ($this->has('primaryDischargeDiagnosisCode_id')) {
            $merge['primaryDischargeDiagnosisCode_id'] = getValueSelectInfinite($this->primaryDischargeDiagnosisCode_id);
        }
        if ($this->has('associatedDischargeDiagnosisCode1_id')) {
            $merge['associatedDischargeDiagnosisCode1_id'] = getValueSelectInfinite($this->associatedDischargeDiagnosisCode1_id);
        }
        if ($this->has('associatedDischargeDiagnosisCode2_id')) {
            $merge['associatedDischargeDiagnosisCode2_id'] = getValueSelectInfinite($this->associatedDischargeDiagnosisCode2_id);
        }
        if ($this->has('mainHospitalizationCupsCode_id')) {
            $merge['mainHospitalizationCupsCode_id'] = getValueSelectInfinite($this->mainHospitalizationCupsCode_id);
        }
        if ($this->has('mainSurgicalProcedureCupsCode_id')) {
            $merge['mainSurgicalProcedureCupsCode_id'] = getValueSelectInfinite($this->mainSurgicalProcedureCupsCode_id);
        }
        if ($this->has('secondarySurgicalProcedureCupsCode_id')) {
            $merge['secondarySurgicalProcedureCupsCode_id'] = getValueSelectInfinite($this->secondarySurgicalProcedureCupsCode_id);
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
