<?php

namespace App\Notifications\Training;

use App\Mail\Training\CancellationTrainingMail;
use App\Models\User;

class CancelTrainingNotification extends Notification
{
    /**
     * Get the array representation of the notification.
     *
     * @codeCoverageIgnore
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(User $notifiable)
    {
        return [
            'userAction' => $notifiable,
            'training' => $this->training,
            'message' => trans('TrainingNotification.title_mail_cancel'),
        ];
    }
}
