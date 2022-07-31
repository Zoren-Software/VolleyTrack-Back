<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class PositionCreateValidator extends Validator
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
                'unique:positions,name',
                'required',
                'min:3',
            ],
            'userId' => [
                'required',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => trans('PositionCreate.name_unique'),
            'name.required' => trans('PositionCreate.name_required'),
            'name.min' => trans('PositionCreate.name_min'),
            'user_id.required' => trans('PositionCreate.user_id_required'),
        ];
    }
}
