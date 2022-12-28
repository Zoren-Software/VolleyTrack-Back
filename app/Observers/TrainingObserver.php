<?php

namespace App\Observers;

use App\Models\Training;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

class TrainingObserver
{
    public function created(Training $training)
    {
        $training->sendNotificationPlayers();
        Subscription::broadcast('notification', $training);
    }

    public function updated(Training $training)
    {
        $training->sendNotificationPlayers();
        Subscription::broadcast('notification', $training);
    }
}
