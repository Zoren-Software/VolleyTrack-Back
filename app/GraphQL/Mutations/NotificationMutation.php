<?php

namespace App\GraphQL\Mutations;

use App\Models\Notification;
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
        $this->notification = $this->notification->find($args['id']);
        $this->notification->update(['read_at' => now()]);

        return $this->notification;
    }
}
