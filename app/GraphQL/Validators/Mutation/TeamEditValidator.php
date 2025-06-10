<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class TeamEditValidator extends Validator
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
            throw new \RuntimeException('O ID da equipe deve ser numérico.');
        }

        /** @var int $id */
        $id = (int) $idRaw;

        return [
            'name' => [
                'unique:teams,name,' . $id,
                'required',
                'min:3',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.unique' => trans('TeamEdit.name_unique'),
            'name.required' => trans('TeamEdit.name_required'),
            'name.min' => trans('TeamEdit.name_min'),
            'user_id.required' => trans('TeamEdit.user_id_required'),
        ];
    }
}
