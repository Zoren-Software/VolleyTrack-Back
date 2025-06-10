<?php

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class PositionEditValidator extends Validator
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
            throw new \RuntimeException('O ID da posição deve ser numérico.');
        }

        /** @var int $id */
        $id = (int) $idRaw;

        return [
            'name' => [
                'unique:positions,name,' . $id,
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
            'name.unique' => trans('PositionEdit.name_unique'),
            'name.required' => trans('PositionEdit.name_required'),
            'name.min' => trans('PositionEdit.name_min'),
            'user_id.required' => trans('PositionEdit.user_id_required'),
        ];
    }
}
