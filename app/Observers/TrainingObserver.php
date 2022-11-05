<?php

namespace App\Observers;

use App\Models\Training;
use App\Models\User;
use App\Notifications\TrainingNotification;

class TrainingObserver
{
    public function created(Training $training)
    {
        $training->team->players()->each(function ($player) use ($training) {
            $player->notify(new TrainingNotification($training));
        });
    }
}
