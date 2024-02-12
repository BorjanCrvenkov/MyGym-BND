<?php

namespace App\Http\Requests;

use App\Enums\FileTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFileRequest extends FormRequest
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
            'file' => [
                'required',
                'file'
            ],
            'file_type' => [
                'required',
                'in:' . FileTypeEnum::getAllValuesAsString()
            ],
            'model_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
