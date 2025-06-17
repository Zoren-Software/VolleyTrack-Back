<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\OwnsPassword;
use App\Rules\PermissionAssignment;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Validation\Validator;

final class UserEditValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        $idRaw = $this->arg('id');

        if (!is_numeric($idRaw)) {
            throw new \RuntimeException('O ID do usuário deve ser numérico.');
        }

        /** @var int $id */
        $id = (int) $idRaw;

        return [
            'name' => [
                'required',
                'min:3',
            ],
            'password' => [
                'sometimes',
                'string',
                'min:6',
                new OwnsPassword($id),
            ],
            'email' => [
                'unique:users,email,' . $id,
                'required',
                'email',
            ],
            'roleId' => [
                'required',
                'exists:roles,id',
                new PermissionAssignment,
            ],
            'cpf' => [
                'nullable',
                Rule::unique('user_information', 'cpf')->ignore($id, 'user_id'),
            ],
            'rg' => [
                'nullable',
                Rule::unique('user_information', 'rg')->ignore($id, 'user_id'),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => trans('UserEdit.name_required'),
            'name.min' => trans('UserEdit.name_min_3'),
            'password.required' => trans('UserEdit.password_required'),
            'password.min' => trans('UserEdit.password_min_6'),
            'email.required' => trans('UserEdit.email_required'),
            'roleId.required' => trans('UserEdit.role_id_required'),
            'email.email' => trans('UserEdit.email_is_valid'),
            'email.unique' => trans('UserEdit.email_unique'),
            'cpf.unique' => trans('UserEdit.cpf_unique'),
            'rg.unique' => trans('UserEdit.rg_unique'),
        ];
    }
}
