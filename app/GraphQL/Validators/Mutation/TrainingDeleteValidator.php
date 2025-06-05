<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\ValidTrainingDeletion;
use Nuwave\Lighthouse\Validation\Validator;

final class TrainingDeleteValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        $ids = $this->arg('id') ?? null;

        return [
            'id' => [
                'required',
                'array',
                'exists:trainings,id',
                new ValidTrainingDeletion,
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.required' => trans('TrainingEdit.id_required'),
        ];
    }
}
