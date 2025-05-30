<?php

namespace App\GraphQL\Queries;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Builder;

class NotificationQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * 
     * @return Builder<Notification>
     */
    public function list($_, array $args): Builder
    {
        $notification = new Notification;

        return $notification->list($args);
    }
}
