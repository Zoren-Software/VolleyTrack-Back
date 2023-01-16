<?php

namespace App\Notifications\Training;

use App\Mail\Training\ConfirmationNotificationTrainingMail;

class NotificationConfirmationTrainingNotification extends Notification
{
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \App\Mail\Training\ConfirmationNotificationTrainingMail
     */
    public function toMail($notifiable)
    {
        return (new ConfirmationNotificationTrainingMail($this->training, $notifiable))
            ->to($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $this->training->team->players;

        return [
            'training' => $this->training,
            'message' => trans('TrainingNotification.title_mail_confirmation'),
        ];
    }
}
