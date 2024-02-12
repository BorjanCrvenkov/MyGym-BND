<?php

namespace App\Http\Requests\ExcelReports;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MembershipTypesBoughtReportRequest extends FormRequest
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
            'start_date'              => [
                'required',
                'date',
            ],
            'end_date'              => [
                'required',
                'date',
                'after:start_date'
            ],
            'gym_id'            => [
                'required',
                'integer',
                'exists:gyms,id',
            ],
        ];
    }
}
