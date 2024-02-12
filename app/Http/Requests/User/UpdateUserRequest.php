<?php

namespace App\Http\Requests\User;

use App\Enums\UserTypesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array|string[]
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

        $rules = $this->{$ruleMethodName}();

        $rules['email'][] = $this->resolveEmailUniqueRule();

        return $rules;
    }

    /**
     * @return Unique
     */
    private function resolveEmailUniqueRule(): Unique
    {
        $userId = $this->route('user');
        $email = $this->request->get('email') ?? null;

        return Rule::unique('users')->where(function ($query) use ($email) {
            $query->where('email', $email);
        })->ignore($userId);
    }

    /**
     * @return string[]
     */
    private function getAdministratorRules(): array
    {
        return [
            'first_name' => [
                'string',
            ],
            'last_name'  => [
                'string',
            ],
            'email'      => [
                'email',
            ],
            'password'   => [
                'string',
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
                'string',
            ],
            'last_name'           => [
                'string',
            ],
            'email'               => [
                'email'
            ],
            'password'            => [
                'string',
            ],
            'date_of_birth'       => [
                'date',
            ],
            'identification_file' => [
                'file',
            ],
            'image'               => [
                'image',
            ],
        ];
    }

    /**
     * @return string[]
     */
    private function getEmployeeRules(): array
    {
        return [
            'first_name'          => [
                'string',
            ],
            'last_name'           => [
                'string',
            ],
            'email'               => [
                'email',
            ],
            'password'            => [
                'string',
            ],
            'date_of_birth'       => [
                'date',
            ],
            'identification_file' => [
                'file',
            ],
            'image'               => [
                'image',
            ],
            'date_of_employment'  => [
                'date',
            ],
            'bio'                 => [
                'string',
            ],
        ];
    }

    /**
     * @return string[]
     */
    private function getMemberRules(): array
    {
        return [
            'first_name'    => [
                'string',
            ],
            'last_name'     => [
                'string',
            ],
            'email'         => [
                'email',
            ],
            'password'      => [
                'string',
            ],
            'username'      => [
                'string',
            ],
            'image'         => [
                'image',
                'nullable',
            ],
            'bio'           => [
                'string',
                'nullable',
            ],
            'date_of_birth' => [
                'date',
                'nullable',
            ],
        ];
    }
}
