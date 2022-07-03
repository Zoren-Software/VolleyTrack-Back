<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class FundamentalCreateValidator extends Validator
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
                'unique:fundamentals,name',
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
            'name.unique' => trans('FundamentalCreate.name_unique'),
            'name.required' => trans('FundamentalCreate.name_required'),
            'name.min' => trans('FundamentalCreate.name_min'),
            'user_id.required' => trans('FundamentalCreate.user_id_required'),
        ];
    }
}
