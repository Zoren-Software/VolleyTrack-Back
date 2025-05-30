<?php

namespace App\Notifications\Training;

use App\Models\User;

class CancelTrainingNotification extends Notification
{
    /**
     * Get the array representation of the notification.
     *
     * @codeCoverageIgnore
     *
     * @param User $notifiable
     * 
     * @return array<string, mixed>
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
