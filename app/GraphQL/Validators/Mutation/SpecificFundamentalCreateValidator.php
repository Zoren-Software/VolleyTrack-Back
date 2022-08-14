<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class SpecificFundamentalCreateValidator extends Validator
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
                'unique:specific_fundamentals,name',
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
            'name.unique' => trans('SpecificFundamentalCreate.name_unique'),
            'name.required' => trans('SpecificFundamentalCreate.name_required'),
            'name.min' => trans('SpecificFundamentalCreate.name_min'),
            'user_id.required' => trans('SpecificFundamentalCreate.user_id_required'),
        ];
    }
}
