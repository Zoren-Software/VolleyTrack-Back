<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\CheckPlayerIsInTraining;
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
        $playerId = $this->arg('playerId') ?? null;
        $trainingId = $this->arg('trainingId') ?? null;

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
            'status.required' => trans('ConfirmTraining.status_required'),
        ];
    }
}
