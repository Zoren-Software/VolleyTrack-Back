<?php

namespace App\Observers;

use App\Models\Training;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

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
        $training->createConfirmationsPlayers();
        #Subscription::broadcast('notification', $training);
    }

    /**
     * @param  Training  $training
     *
     * NOTE - Ignorado nos testes unitários por ser um método que envia notificação
     *
     * 
     *
     * @return void
     */
    public function updated(Training $training)
    {
        $training->createConfirmationsPlayers();
        #Subscription::broadcast('notification', $training);

        //dd($training->getOriginal('status'), $training->status);
        if ($training->getOriginal('status')) {
            if ($training->status == 0) {
                $training->sendNotificationPlayersTrainingCancelled();
            }
        }
    }
}
