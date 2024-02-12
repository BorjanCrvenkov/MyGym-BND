<?php

namespace App\Http\Requests;

use App\Enums\ReviewTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
            'rating' => [
                'integer',
                'min:0',
                'max:5',
            ],
            'body' => [
                'string',
            ],
            'reviewer_id' => [
                'integer',
                'exists:users,id',
            ],
            'model_id' => [
                'integer',
            ],
            'model_type' => [
                'string',
                'in:' . ReviewTypeEnum::getAllValuesAsString(),
            ],
        ];
    }
}
