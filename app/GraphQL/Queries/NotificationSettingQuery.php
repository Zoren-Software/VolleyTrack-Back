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
        return NotificationSetting::query()->list($args);
    }
}
