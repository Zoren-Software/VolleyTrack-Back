<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class NotificationMutation
{
    /**
     * @param  array<string, mixed>  $args
     */
    public function notificationsRead($rootValue, array $args, GraphQLContext $context)
    {
        $user = $context->user();

        // NOTE - Checa se deve marcar apenas uma notificação como lida
        if (isset($args['id']) && $args['id']) {
            $user->notifications()
                ->whereNull('read_at')
                ->whereIn('id', $args['id'])
                ->update(['read_at' => now()]);

            return [
                'message' => trans('NotificationRead.read_notification'),
            ];
        }

        // NOTE - Checa se deve marcar todas as notificações como lidas
        if (isset($args['mark_all_as_read']) && $args['mark_all_as_read']) {
            $user->notifications()
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            return [
                'message' => trans('NotificationRead.read_all_notifications'),
            ];
        }

        $readCount = $args['recent_to_delete_count'] ?? 0;

        $notificationsToRead = $user->notifications()
            ->whereNull('read_at')
            ->latest()
            ->take($readCount)
            ->get();

        $notificationsToRead->each(function ($notification) {
            $notification->update(['read_at' => now()]);
        });

        $actualReadCount = $notificationsToRead->count();

        return [
            'message' => trans('NotificationRead.recent_notifications_read', ['count' => $actualReadCount]),
        ];
    }
}
