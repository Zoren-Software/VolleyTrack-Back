<?php

namespace App\Observers;

use App\Models\Training;
use App\Models\User;
use App\Notifications\TrainingNotification;

class TrainingObserver
{
    public function created(Training $training)
    {
        $training->sendNotificationPlayers();
    }

    public function updated(Training $training)
    {
        $training->sendNotificationPlayers();
    }
}
