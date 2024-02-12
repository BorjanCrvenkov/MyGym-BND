<?php

namespace App\Http\Requests;

use App\Enums\ExpenseStatusEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'status'          => [
                'string',
                'in:' . ExpenseStatusEnum::getAllValuesAsString(),
            ],
            'expense_type_id' => [
                'integer',
                'exists:expense_types,id'
            ],
        ];
    }
}
