<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;
use App\Rules\CheckPlayerIsInTraining;

final class ConfirmTrainingValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required_with:player_id,training_id'
            ],
            'playerId' => [
                'required_with:id',
                new CheckPlayerIsInTraining($this->arg('playerId'), $this->arg('trainingId')),
            ],
            'trainingId' => [
                'required_with:id'
            ],
            'status' => [
                'required'
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.required_without_all' => 'É necessário informar o id ou player_id e training_id',
            'playerId.required_without_all' => 'É necessário informar o id ou player_id e training_id',
            'trainingId.required_without_all' => 'É necessário informar o id ou player_id e training_id',
            'status.required' => 'É necessário informar o status',
            'status.in' => 'O status deve ser confirmed ou not_confirmed',
        ];
    }
}
