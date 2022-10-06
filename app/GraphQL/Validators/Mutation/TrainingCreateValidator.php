<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class TrainingCreateValidator extends Validator
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
            'name.unique' => trans('TrainingCreate.name_unique'),
            'name.required' => trans('TrainingCreate.name_required'),
            'name.min' => trans('TrainingCreate.name_min'),
            'user_id.required' => trans('TrainingCreate.user_id_required'),
        ];
    }
}
