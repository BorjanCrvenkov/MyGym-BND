<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseTypeRequest extends FormRequest
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
                'string',
            ],
            'description'                    => [
                'nullable',
                'string',
            ],
            'cost'                           => [
                'numeric',
            ],
            'recurring'                      => [
                'boolean',
            ],
            'recurring_every_number_of_days' => [
                'nullable',
                'required_if:recurring,true',
                'integer'
            ],
            'next_recurring_date' => [
                'nullable',
                'date',
            ],
            'gym_id'                         => [
                'exists:gyms,id',
            ],
        ];
    }
}
