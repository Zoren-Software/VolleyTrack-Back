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
        $playerId = $this->arg('playerId');
        $trainingId = $this->arg('trainingId');

        if (!is_numeric($playerId) || !is_numeric($trainingId)) {
            throw new \RuntimeException('O ID do jogador e do treinamento devem ser numéricos.');
        }

        /** @var int $playerId */
        $playerId = (int) $playerId;
        /** @var int $trainingId */
        $trainingId = (int) $trainingId;

        return [
            'id' => [
                'required',
            ],
            'playerId' => [
                'required',
                new CheckPlayerIsInTraining($playerId, $trainingId),
            ],
            'trainingId' => [
                'required',
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

        ];
    }
}
