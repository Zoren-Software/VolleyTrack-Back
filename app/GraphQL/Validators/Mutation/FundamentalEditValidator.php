<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class FundamentalEditValidator extends Validator
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
                'unique:fundamentals,name,' . $this->arg('id'),
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
            'name.unique' => trans('FundamentalEdit.name_unique'),
            'name.required' => trans('FundamentalEdit.name_required'),
            'name.min' => trans('FundamentalEdit.name_min'),
            'user_id.required' => trans('FundamentalEdit.user_id_required'),
        ];
    }
}
