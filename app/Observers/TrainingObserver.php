<?php

namespace App\Observers;

use App\Models\Training;

class TrainingObserver
{
    /**
     * @param  Training  $training
     *
     * NOTE - Ignorado nos testes unitários por ser um método que envia notificação
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function created(Training $training)
    {
        $training->confirmationsPlayers();
    }

    /**
     * @param  Training  $training
     *
     * NOTE - Ignorado nos testes unitários por ser um método que envia notificação
     *
     * @codeCoverageIgnore
     *
     * @return void
     */
    public function updated(Training $training)
    {
        if ($training->isDirty('team_id')) {
            $originalTeamId = $training->getOriginal('team_id');
            $training->deleteConfirmationsPlayersOld($originalTeamId);
        }

        $training->confirmationsPlayers($training->id);

        if ($training->getOriginal('status') && $training->status == 0) {
            $training->sendNotificationPlayersTrainingCancelled();
            $training->sendEmailPlayersTrainingCancelled();
        }
    }

    /**
     * @return void
     */
    public function creating(Training $training)
    {
        if (!$training->isDirty('user_id')) {
            $user = auth()->user();

            if ($user instanceof \App\Models\User) {
                $training->user_id = $user->id;
            }
        }
    }

    /**
     * @return void
     */
    public function updating(Training $training)
    {
        if (!$training->isDirty('user_id')) {
            $user = auth()->user();

            if ($user instanceof \App\Models\User) {
                $training->user_id = $user->id;
            }
        }
    }
}
