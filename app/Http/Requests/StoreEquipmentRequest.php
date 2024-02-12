<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEquipmentRequest extends FormRequest
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
            'name'              => [
                'required',
                'string'
            ],
            'description'       => [
                'required',
                'string',
            ],
            'price'             => [
                'required',
                'numeric',
            ],
            'last_service_date' => [
                'nullable',
                'date',
            ],
            'next_service_date' => [
                'nullable',
                'date',
                'after:' . now()->subDay()->toDateString(),
            ],
            'gym_id'            => [
                'required',
                'integer',
                'exists:gyms,id',
            ],
            'image'             => [
                'required',
                'image',
            ],
        ];
    }
}
