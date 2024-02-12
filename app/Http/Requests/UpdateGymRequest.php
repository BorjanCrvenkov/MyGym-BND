<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGymRequest extends FormRequest
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
            'name'           => [
                'string',
            ],
            'address'        => [
                'string',
            ],
            'date_opened'    => [
                'date',
            ],
            'working_times'  => [
                'json',
            ],
            'phone_number'   => [
                'string',
            ],
            'cover_image'    => [
                'image',
            ],
            'images'         => [
                'array',
            ],
            'images.*'       => [
                'image',
            ],
            'owner_id'       => [
                'integer',
                'exists:users,id'
            ],
            'email'          => [
                'email',
            ],
            'instagram_link' => [
                'string',
            ],
            'facebook_link'  => [
                'string',
            ],
            'twitter_link'   => [
                'string',
            ],
            'shutdown_date'   => [
                'date',
                'nullable',
                'after:' . now()->addDay()->toDateString(),
            ],
        ];
    }
}
