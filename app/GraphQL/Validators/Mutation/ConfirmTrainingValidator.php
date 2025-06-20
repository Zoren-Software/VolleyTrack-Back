<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\CheckPlayerIsInTraining;
use App\Rules\CheckTrainingCancelled;
use Nuwave\Lighthouse\Validation\Validator;

final class ConfirmTrainingValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        $rawPlayerId = $this->arg('playerId');
        $rawTrainingId = $this->arg('trainingId');

        /** @var int $playerId */
        $playerId = is_numeric($rawPlayerId) ? (int) $rawPlayerId : 0;

        /** @var int $trainingId */
        $trainingId = is_numeric($rawTrainingId) ? (int) $rawTrainingId : 0;

        return [
            'id' => [
                'required',
            ],
            'playerId' => [
                'required',
                'integer',
                new CheckPlayerIsInTraining($playerId, $trainingId),
            ],
            'trainingId' => [
                'required',
                'integer',
                'exists:trainings,id',
                new CheckTrainingCancelled($trainingId),
            ],
            'status' => [
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
            'playerId.required' => trans('ConfirmTraining.playerId_required'),
            'trainingId.required' => trans('ConfirmTraining.trainingId_required'),
            'trainingId.exists' => trans('ConfirmTraining.trainingId_exists'),
            'status.required' => trans('ConfirmTraining.status_required'),
            'playerId.integer' => trans('ConfirmTraining.playerId_integer'),
            'trainingId.integer' => trans('ConfirmTraining.trainingId_integer'),
        ];
    }
}
