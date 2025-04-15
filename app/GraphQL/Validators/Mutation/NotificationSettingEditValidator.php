<?php

namespace App\GraphQL\Validators\Mutation;

use App\Models\Training;
use Nuwave\Lighthouse\Validation\Validator;

class NotificationSettingEditValidator extends Validator
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
                'required',
                'integer',
                'exists:notification_settings,id',
            ],
            'notificationTypeId' => [
                'required',
                'integer',
                'exists:notification_types,id',
            ],
            'viaEmail' => [
                'required',
                'boolean',
            ],
            'viaSystem' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * TODO - Add custom messages for validation rules.
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            
        ];
    }
}
