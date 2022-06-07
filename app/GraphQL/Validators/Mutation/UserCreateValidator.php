<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

class UserCreateValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'min:6',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
        ]; 
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => trans('UserCreate.password_required'),
            'password.min' => trans('UserCreate.password_min_6'),
            'email.required' => trans('UserCreate.email_required'),
            'email.email' => trans('UserCreate.email_is_valid'),
            'email.unique' => trans('UserCreate.email_unique'),
        ];
    }
}
