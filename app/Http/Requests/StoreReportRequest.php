<?php

namespace App\Http\Requests;

use App\Enums\ReportTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
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
            'model_id'   => [
                'required',
                'integer',
            ],
            'model_type' => [
                'required',
                'string',
                'in:' . ReportTypeEnum::getAllValuesAsString(),
            ],
            'reason'     => [
                'required',
                'string',
            ],
            'heading' => [
                'required',
                'string',
            ],
        ];
    }
}
