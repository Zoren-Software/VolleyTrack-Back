<?php

namespace App\GraphQL\Validators\Mutation;

use App\Rules\CannotDeleteOwnAccount;
use Nuwave\Lighthouse\Validation\Validator;

final class UserDeleteValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        $id = $this->arg('id');

        if (!is_array($id)) {
            throw new \RuntimeException('ID deve ser um array');
        }

        foreach ($id as $singleId) {
            if (!is_numeric($singleId)) {
                throw new \RuntimeException('Todos os IDs devem ser numéricos');
            }
        }

        /** @var array<int> $idList */
        $idList = [];

        foreach ($id as $singleId) {
            /** @var string|int $singleId */
            $idList[] = (int) $singleId;
        }

        return [
            'id' => [
                'required',
                'exists:users,id',
                new CannotDeleteOwnAccount($idList),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.required' => trans('UserDelete.ids_required'),
            'id.exists' => trans('UserDelete.ids_exists'),
        ];
    }
}
