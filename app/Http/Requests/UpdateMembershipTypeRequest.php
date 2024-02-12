<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMembershipTypeRequest extends FormRequest
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
            'name'           => [
                'string',
            ],
            'description'    => [
                'string',
            ],
            'price'          => [
                'numeric',
            ],
            'duration_weeks' => [
                'integer',
            ],
            'gym_id' => [
                'integer',
                'exists:gyms,id'
            ],
        ];
    }
}
