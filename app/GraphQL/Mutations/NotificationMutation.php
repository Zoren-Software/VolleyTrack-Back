<?php

namespace App\GraphQL\Mutations;

use App\Models\Notification;
use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class NotificationMutation
{
    private Notification $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function notificationRead($rootValue, array $args, GraphQLContext $context)
    {
        $user = User::find($context->user()->id);
        $user->unreadNotifications()->update(['read_at' => now()]);

        return [
            'message' => 'Todas as notificações foram lidas com sucesso!',
        ];
    }
}
