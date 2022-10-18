<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class ConfigEditValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'userId' => [
                'required',
            ],
            'nameTenant' => [
                'required',
                'min:3',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => trans('ConfigEdit.user_id_required'),
            'nameTenant.required' => trans('ConfigEdit.name_tenant_required'),
            'nameTenant.min' => trans('ConfigEdit.name_tenant_min'),
        ];
    }
}
