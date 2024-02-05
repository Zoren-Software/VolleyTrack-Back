<?php

namespace App\GraphQL\Validators\Mutation;

use App\Models\Training;
use Nuwave\Lighthouse\Validation\Validator;

class NotificationsReadValidator extends Validator
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
                'sometimes',
                'array',
            ],
            'mark_all_as_read' => [
                'sometimes',
                'boolean',
            ],
            'recent_to_delete_count' => [
                'sometimes',
                'integer',
                'min:1',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
        ];
    }
}
