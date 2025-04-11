<?php

namespace App\Notifications\Training;

use App\Mail\Training\TrainingMail;

class TrainingNotification extends Notification
{
    /**
     * Get the array representation of the notification.
     *
     * @codeCoverageIgnore
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'training' => $this->training,
            'confirmationTraining' => $this->confirmationTraining,
            'message' => trans('TrainingNotification.title_mail'),
        ];
    }
}
