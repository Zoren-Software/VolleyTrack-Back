<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\CannotDeleteOwnAccount;
use Nuwave\Lighthouse\Validation\Validator;

final class UserDeleteValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'exists:users,id',
                new CannotDeleteOwnAccount($this->arg('id')),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.required' => trans('UserDelete.ids_required'),
            'id.exists' => trans('UserDelete.ids_exists'),
        ];
    }
}
