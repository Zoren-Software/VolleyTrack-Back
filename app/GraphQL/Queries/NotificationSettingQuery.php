<?php

namespace App\GraphQL\Queries;

use App\Models\NotificationSetting;

class NotificationSettingQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     */
    public function list($_, array $args)
    {
        $notificationSetting = new NotificationSetting;

        return $notificationSetting->list($args);
    }
}
