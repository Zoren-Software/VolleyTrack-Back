<?php

namespace App\GraphQL\Validators\Mutation;

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
        return [
            'name' => [
                'required',
                'min:3',
            ],
            'userId' => [
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
            'name.required' => trans('TrainingEdit.name_required'),
            'name.min' => trans('TrainingEdit.name_min'),
            'user_id.required' => trans('TrainingEdit.user_id_required'),
        ];
    }
}
