<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkingTimeRequest extends FormRequest
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
            'start_time'          => [
                'date',
            ],
            'end_time'            => [
                'date',
                'after_or_equal::start_time',
            ],
            'working_schedule_id' => [
                'integer',
                'exists:working_schedules,id',
            ],
        ];
    }
}
