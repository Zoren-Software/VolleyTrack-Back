<?php

namespace App\GraphQL\Validators\Mutation;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class UserSetPasswordValidator extends Validator
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
            'passwordConfirmation' => [
                'required',
                'same:password',
            ],
            'email' => [
                'required',
                'email',
            ],
            'token' => [
                'required',
                'string',
                Rule::exists('users', 'set_password_token'),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => trans('UserSetPassword.password_required'),
            'password.min' => trans('UserSetPassword.password_min_6'),
            'passwordConfirmation.required' => trans('UserSetPassword.password_confirmation_required'),
            'passwordConfirmation.same' => trans('UserSetPassword.password_confirmation_same'),
            'email.required' => trans('UserSetPassword.email_required'),
            'email.email' => trans('UserSetPassword.email_is_valid'),
            'token.required' => trans('UserSetPassword.token_required'),
            'token.string' => trans('UserSetPassword.token_string'),
            'token.exists' => trans('UserSetPassword.token_exists'),
        ];
    }
}
