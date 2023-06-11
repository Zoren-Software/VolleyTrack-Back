<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\PermissionAssignment;
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
            'roleId' => [
                'required',
                'exists:roles,id',
                new PermissionAssignment(),
            ],
            'cpf' => [
                'unique:user_information,cpf',
            ],
            'rg' => [
                'unique:user_information,rg',
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
            'roleId.required' => trans('UserCreate.role_id_required'),
            'email.email' => trans('UserCreate.email_is_valid'),
            'email.unique' => trans('UserCreate.email_unique'),
            'cpf.unique' => trans('UserCreate.cpf_unique'),
            'rg.unique' => trans('UserCreate.rg_unique')
        ];
    }
}
