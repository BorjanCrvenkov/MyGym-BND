<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
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
                'required',
                'string',
            ],
            'stripe_plan'                 => [
                'required',
                'string',
            ],
            'price'                       => [
                'required',
                'numeric',
            ],
            'description'                 => [
                'required',
                'string',
            ],
            'number_of_allowed_gyms'      => [
                'required',
                'integer',
            ],
            'number_of_allowed_employees' => [
                'required',
                'integer',
            ],
            'duration_months'             => [
                'required',
                'integer',
            ],
        ];
    }
}
