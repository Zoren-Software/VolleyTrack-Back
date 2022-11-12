<?php

namespace App\Observers;

use App\Models\Training;
use App\Models\User;
use App\Notifications\TrainingNotification;

class TrainingObserver
{
    public function created(Training $training)
    {
        $this->notifyChange($training);
    }

    public function updated(Training $training)
    {
        $this->notifyChange($training);
    }

    public function notifyChange(Training $training)
    {
        $training->team->players()->each(function ($player) use ($training) {
            if ($training->date_start->isToday()) {
                $player->notify(new TrainingNotification($training));
            }
        });
    }
}
