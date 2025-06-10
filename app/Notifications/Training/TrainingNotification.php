<?php

namespace App\Notifications\Training;

class TrainingNotification extends Notification
{
    /**
     * Get the array representation of the notification.
     *
     * @codeCoverageIgnore
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
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
