<?php

namespace App\GraphQL\Queries;

use App\Models\Notification;

class NotificationQuery
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $notification = new Notification();

        return $notification->list($args);
    }
}
