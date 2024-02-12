<?php

namespace App\Http\Requests;

use App\Enums\ReviewTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
                'required',
                'integer',
                'min:0',
                'max:5',
            ],
            'body' => [
                'required',
                'string',
            ],
            'reviewer_id' => [
                'integer',
                'exists:users,id',
            ],
            'model_id' => [
                'required',
                'integer',
            ],
            'model_type' => [
                'required',
                'string',
                'in:' . ReviewTypeEnum::getAllValuesAsString(),
            ],
        ];
    }
}
