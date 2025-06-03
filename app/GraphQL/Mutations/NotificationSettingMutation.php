<?php

namespace App\GraphQL\Mutations;

use App\Models\NotificationSetting;
use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class NotificationSettingMutation
{
    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return NotificationSetting
     */
    public function notificationEdit($rootValue, array $args, GraphQLContext $context): NotificationSetting
    {
        $user = $context->user();

        if (!$user instanceof User) {
            throw new \Exception('User not authenticated.');
        }

        $args['user_id'] = $user->id;

        /** @var NotificationSetting $notificationSetting */
        $notificationSetting = NotificationSetting::findOrFail($args['id']);
        $notificationSetting->fill($args);
        $notificationSetting->save();
        $notificationSetting->refresh();

        return $notificationSetting;
    }
}
