<?php

namespace App\Http\Requests\User;

use App\Enums\UserTypesEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        $userType = $this->request->get('user_type');

        if (!$userType || !in_array($userType, UserTypesEnum::getAllValuesAsArray())) {

            return [
                'user_type' => 'required|string|in:' . UserTypesEnum::getAllValuesAsString(),
            ];
        }

        $userType = ucfirst($userType);

        $ruleMethodName = "get{$userType}Rules";

        return $this->{$ruleMethodName}();
    }

    /**
     * @return string[]
     */
    private function getAdministratorRules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
            ],
            'last_name'  => [
                'required',
                'string',
            ],
            'email'      => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password'   => [
                'required',
                'string',
            ],
            'user_type'  => [
                'required',
                'in:' . UserTypesEnum::ADMINISTRATOR->value,
            ],
        ];
    }

    /**
     * @return string[]
     */
    private function getBusinessRules(): array
    {
        return [
            'first_name'          => [
                'required',
                'string',
            ],
            'last_name'           => [
                'required',
                'string',
            ],
            'email'               => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password'            => [
                'required',
                'string',
            ],
            'date_of_birth'       => [
                'required',
                'date'
            ],
            'identification_file' => [
                'required',
                'file',
            ],
            'image'               => [
                'required',
                'image',
            ],
            'user_type'           => 'required|in:' . UserTypesEnum::BUSINESS->value,
        ];
    }

    /**
     * @return string[]
     */
    private function getEmployeeRules(): array
    {
        return [
            'first_name'          => [
                'required',
                'string',
            ],
            'last_name'           => [
                'required',
                'string',
            ],
            'email'               => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password'            => [
                'required',
                'string',
            ],
            'date_of_birth'       => [
                'date',
            ],
            'identification_file' => [
                'required',
                'file',
            ],
            'image'               => [
                'required',
                'image',
            ],
            'date_of_employment'  => [
                'required',
                'date',
            ],
            'bio'                 => [
                'string',
            ],
            'user_type'           => [
                'required',
                'in:' . UserTypesEnum::EMPLOYEE->value,
            ],
            'gym_id'              => [
                'required',
                'integer',
                'exists:gyms,id',
            ],
        ];
    }

    /**
     * @return string[]
     */
    private function getMemberRules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
            ],
            'last_name'  => [
                'required',
                'string',
            ],
            'email'      => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password'   => [
                'required',
                'string',
            ],
            'username'   => [
                'required',
                'string',
            ],
            'image'      => [
                'required',
                'image',
            ],
            'user_type'  => [
                'required',
                'in:' . UserTypesEnum::MEMBER->value,
            ],
            'bio'                 => [
                'string',
                'nullable',
            ],
            'date_of_birth'       => [
                'date',
                'nullable',
            ],
        ];
    }
}
