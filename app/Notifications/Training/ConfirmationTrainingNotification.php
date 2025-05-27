<?php

namespace App\Notifications\Training;

use App\Models\User;

class ConfirmationTrainingNotification extends Notification
{
    /**
     * Get the array representation of the notification.
     *
     * @codeCoverageIgnore
     *
     * @return array
     */
    public function toArray(User $notifiable)
    {
        return [
            'userAction' => $notifiable,
            'training' => $this->training,
            'message' => trans('TrainingNotification.title_mail_confirmation'),
        ];
    }
}
