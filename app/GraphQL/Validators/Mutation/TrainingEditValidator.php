<?php

namespace App\GraphQL\Validators\Mutation;

use App\Models\Training;
use Nuwave\Lighthouse\Validation\Validator;

final class TrainingEditValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        $fundamentalIds = $this->arg('fundamentalId') ?? [];

        return Training::rules($fundamentalIds);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => trans('TrainingEdit.name_required'),
            'name.min' => trans('TrainingEdit.name_min'),
            'teamId.required' => trans('TrainingEdit.team_id_required'),
            'dateStart.required' => trans('TrainingEdit.date_start_required'),
            'dateStart.date' => trans('TrainingEdit.date_start_type_date'),
            'dateStart.date_format' => trans('TrainingEdit.date_start_date_format'),
            'dateStart.before' => trans('TrainingEdit.date_start_before'),
            'dateEnd.required' => trans('TrainingEdit.date_end_required'),
            'dateEnd.date' => trans('TrainingEdit.date_end_type_date'),
            'dateEnd.date_format' => trans('TrainingEdit.date_end_date_format'),
            'dateEnd.after' => trans('TrainingEdit.date_end_after'),
        ];
    }
}
