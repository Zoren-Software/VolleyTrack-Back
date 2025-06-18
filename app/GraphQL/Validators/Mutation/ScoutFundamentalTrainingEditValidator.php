<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class ScoutFundamentalTrainingEditValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        $idRaw = $this->arg('id');

        if (!is_numeric($idRaw)) {
            throw new \RuntimeException('O ID do fundamental específico deve ser numérico.');
        }

        /** @var int $id */
        $id = (int) $idRaw;

        return [
            'id' => [
                'required',
                'exists:scout_fundamentals_training,id,' . $id,
            ],
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
