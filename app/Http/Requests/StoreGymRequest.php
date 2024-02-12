<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreGymRequest extends FormRequest
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
                'required',
                'string',
            ],
            'address'        => [
                'required',
                'string',
            ],
            'date_opened'    => [
                'required',
                'date',
            ],
            'working_times'  => [
                'required',
                'json',
            ],
            'phone_number'   => [
                'string',
            ],
            'cover_image'    => [
                'required',
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
        ];
    }
}
