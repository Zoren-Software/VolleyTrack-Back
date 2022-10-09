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
                'date_format:Y-m-d H:i:s',
                'before:dateEnd',
            ],
            'dateEnd' => [
                'required',
                'date',
                'date_format:Y-m-d H:i:s',
                'after:dateStart',
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
            'teamId.required' => trans('TrainingCreate.team_id_required'),
            'userId.required' => trans('TrainingCreate.user_id_required'),
            'dateStart.required' => trans('TrainingCreate.date_start_required'),
            'dateStart.date' => trans('TrainingCreate.date_start_type_date'),
            'dateStart.date_format' => trans('TrainingCreate.date_start_date_format'),
            'dateStart.before' => trans('TrainingCreate.date_start_before'),
            'dateEnd.required' => trans('TrainingCreate.date_end_required'),
            'dateEnd.date' => trans('TrainingCreate.date_end_type_date'),
            'dateEnd.date_format' => trans('TrainingCreate.date_end_date_format'),
            'dateEnd.after' => trans('TrainingCreate.date_end_after'),
        ];
    }
}
