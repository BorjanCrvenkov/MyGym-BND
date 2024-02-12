<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseTypeRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'                           => [
                'required',
                'string',
            ],
            'description'                    => [
                'nullable',
                'string',
            ],
            'cost'                           => [
                'required',
                'numeric',
            ],
            'recurring'                      => [
                'required',
                'boolean',
            ],
            'recurring_every_number_of_days' => [
                'required_if:recurring,true',
                'nullable',
                'integer',
            ],
            'gym_id'                         => [
                'required',
                'exists:gyms,id',
            ],
        ];
    }
}
