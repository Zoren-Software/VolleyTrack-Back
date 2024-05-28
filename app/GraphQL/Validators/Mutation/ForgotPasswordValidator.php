<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class ForgotPasswordValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => trans('UserSetPassword.email_required'),
            'email.email' => trans('UserSetPassword.email_is_valid'),
        ];
    }
}
