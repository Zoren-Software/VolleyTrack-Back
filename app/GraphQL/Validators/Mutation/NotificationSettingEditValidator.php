<?php

namespace App\GraphQL\Validators\Mutation;

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
     */
    public function messages(): array
    {
        return [
            'id.required' => trans('NotificationSettingEdit.id_required'),
            'id.integer' => trans('NotificationSettingEdit.id_integer'),
            'id.exists' => trans('NotificationSettingEdit.id_exists'),
            'notificationTypeId.required' => trans('NotificationSettingEdit.notificationTypeId_required'),
            'notificationTypeId.integer' => trans('NotificationSettingEdit.notificationTypeId_integer'),
            'notificationTypeId.exists' => trans('NotificationSettingEdit.notificationTypeId_exists'),
            'viaEmail.required' => trans('NotificationSettingEdit.viaEmail_required'),
            'viaEmail.boolean' => trans('NotificationSettingEdit.viaEmail_boolean'),
            'viaSystem.required' => trans('NotificationSettingEdit.viaSystem_required'),
            'viaSystem.boolean' => trans('NotificationSettingEdit.viaSystem_boolean'),
        ];
    }
}
