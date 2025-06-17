<?php

namespace App\GraphQL\Queries;

use App\Models\NotificationSetting;
use Illuminate\Database\Eloquent\Builder;

class NotificationSettingQuery
{
    /**
     * @param  mixed  $_
     * @param  array{}  $args
     * @return Builder<NotificationSetting>
     */
    public function list($_, array $args): Builder
    {
        return NotificationSetting::query()->list($args);
    }
}
