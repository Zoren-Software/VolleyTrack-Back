<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class NotificationMutation
{
    private ?User $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function notificationsRead($rootValue, array $args, GraphQLContext $context)
    {
        $this->user = $this->user->find($context->user()->id);
        $this->user->unreadNotifications()->update(['read_at' => now()]);

        return [
            'message' => trans('NotificationRead.read_all_notifications'),
        ];
    }
}
