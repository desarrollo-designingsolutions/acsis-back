<?php

namespace App\Http\Requests\GlosaAnswer;

use App\Helpers\Constants;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class GlosaAnswerStoreRequest extends FormRequest
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
            'user_id' => 'required',
            'company_id' => 'required',
            'glosa_id' => 'required',
            'observation' => 'required',
            'file' => 'required',
            'date_answer' => 'required|date',
            'value_approved' => 'required',
            'value_accepted' => 'required',
            'status' => 'required',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'El campo es obligatorio',
            'company_id.required' => 'El campo es obligatorio',
            'glosa_id.required' => 'El campo es obligatorio',
            'observation.required' => 'El campo es obligatorio',
            'file.required' => 'El campo es obligatorio',
            'date_answer.required' => 'El campo es obligatorio',
            'date_answer.date' => 'El campo debe ser una fecha',
            'value_approved.required' => 'El campo es obligatorio',
            'value_accepted.required' => 'El campo es obligatorio',
            'status.required' => 'El campo es obligatorio',
        ];
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('code_glosa_answer_id')) {
            $merge['code_glosa_answer_id'] = getValueSelectInfinite($this->code_glosa_answer_id);
        }

        if ($this->has('value_approved')) {
            $merge['value_approved'] = ! is_numeric($this->value_approved) ? floatval($this->value_approved) : $this->value_approved;
        }

        if ($this->has('value_accepted')) {
            $merge['value_accepted'] = ! is_numeric($this->value_accepted) ? floatval($this->value_accepted) : $this->value_accepted;
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
