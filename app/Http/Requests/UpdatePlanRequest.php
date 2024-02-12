<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePlanRequest extends FormRequest
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
            'name'                        => [
                'string',
            ],
            'stripe_plan'                 => [
                'string',
            ],
            'price'                       => [
                'numeric',
            ],
            'description'                 => [
                'string',
            ],
            'number_of_allowed_gyms'      => [
                'integer',
            ],
            'number_of_allowed_employees' => [
                'integer',
            ],
            'duration_months'             => [
                'integer',
            ],
        ];
    }
}
