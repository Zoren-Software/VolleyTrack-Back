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
                'required',
                'min:3',
            ],
            'userId' => [
                'required',
            ],
            'teamId' => [
                'required',
            ],
            'dateStart' => [
                'required',
                'date',
            ],
            'dateEnd' => [
                'required',
                'date',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => trans('TrainingCreate.name_required'),
            'name.min' => trans('TrainingCreate.name_min'),
            'team_id.required' => trans('TrainingCreate.team_id_required'),
            'user_id.required' => trans('TrainingCreate.user_id_required'),
        ];
    }
}
