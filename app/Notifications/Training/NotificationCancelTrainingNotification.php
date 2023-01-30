<?php

namespace App\Notifications\Training;

use App\Mail\Training\CancellationNotificationTrainingMail;
use App\Models\User;

class NotificationCancelTrainingNotification extends Notification
{
    /**
     * Get the mail representation of the notification.
     *
     * @codeCoverageIgnore
     *
     * @param  mixed  $notifiable
     * @return \App\Mail\Training\CancellationNotificationTrainingMail
     */
    public function toMail(User $notifiable)
    {
        return (new CancellationNotificationTrainingMail($this->training, $notifiable))
            ->to($notifiable->email);
    }

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
        $this->training->team->players;

        return [
            'training' => $this->training,
            'message' => trans('TrainingNotification.title_mail_cancel'),
        ];
    }
}
