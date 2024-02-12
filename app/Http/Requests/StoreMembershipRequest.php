<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMembershipRequest extends FormRequest
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
            'start_date'         => [
                'required',
                'date',
            ],
            'user_id'            => [
                'integer',
                'exists:users,id',
            ],
            'membership_type_id' => [
                'required',
                'integer',
                'exists:membership_types,id',
            ],
            'payment_method_id' => [
                'required',
            ],
        ];
    }
}
