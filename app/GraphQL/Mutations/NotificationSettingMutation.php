<?php

namespace App\GraphQL\Mutations;

use App\Models\NotificationSetting;
use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class NotificationSettingMutation
{
    private ?User $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    /**
     * @param mixed $rootValue
     * @param array<string, mixed> $args
     */
    public function notificationEdit($rootValue, array $args, GraphQLContext $context)
    {
        $user = $context->user();

        if (! $user instanceof User) {
            throw new \Exception('User not authenticated.');
        }

        $args['user_id'] = $user->id;

        $notificationSetting = NotificationSetting::findOrFail($args['id']);
        $notificationSetting->fill($args);
        $notificationSetting->save();
        $notificationSetting->refresh();

        return $notificationSetting;
    }
}
