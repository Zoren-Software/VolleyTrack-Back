<?php

namespace App\Notifications\Training;

use App\Mail\Training\NotificationTrainingMail;

class TrainingNotification extends Notification
{
    /**
     * Get the mail representation of the notification.
     *
     * @codeCoverageIgnore
     * @param  mixed  $notifiable
     * @return \App\Mail\Training\NotificationTrainingMail
     */
    public function toMail($notifiable)
    {
        return (new NotificationTrainingMail($this->training, $notifiable))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @codeCoverageIgnore
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'training' => $this->training,
            'message' => trans('TrainingNotification.title_mail'),
        ];
    }
}
