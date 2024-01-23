<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class TeamEditValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'unique:teams,name,' . $this->arg('id'),
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
            'name.unique' => trans('TeamEdit.name_unique'),
            'name.required' => trans('TeamEdit.name_required'),
            'name.min' => trans('TeamEdit.name_min'),
            'user_id.required' => trans('TeamEdit.user_id_required'),
        ];
    }
}
