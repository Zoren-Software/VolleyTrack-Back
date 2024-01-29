<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class TeamCreateValidator extends Validator
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
                'unique:teams,name',
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
            'name.unique' => trans('TeamCreate.name_unique'),
            'name.required' => trans('TeamCreate.name_required'),
            'name.min' => trans('TeamCreate.name_min'),
        ];
    }
}
