<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class ScoutFundamentalTrainingCreateValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'playerId' => [
                'required',
                'exists:users,id',
            ],
            'trainingId' => [
                'required',
                'exists:trainings,id',
            ],
            'positionId' => [
                'required',
                'exists:positions,id',
            ],
        ];
    }

    /**
     * Return the validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'player_id.required' => trans('ScoutFundamentalTrainingCreate.player_id_required'),
            'player_id.exists' => trans('ScoutFundamentalTrainingCreate.player_id_exists'),
            'training_id.required' => trans('ScoutFundamentalTrainingCreate.training_id_required'),
            'training_id.exists' => trans('ScoutFundamentalTrainingCreate.training_id_exists'),
            'position_id.required' => trans('ScoutFundamentalTrainingCreate.position_id_required'),
            'position_id.exists' => trans('ScoutFundamentalTrainingCreate.position_id_exists'),
        ];
    }
}
