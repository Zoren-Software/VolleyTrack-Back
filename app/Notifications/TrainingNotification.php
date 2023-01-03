<?php

namespace App\Notifications;

use App\Mail\NotificationTrainingMail;
use Illuminate\Bus\Queueable;
use App\Models\Training;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\TrainingConfig;

class TrainingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $training;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Training $training)
    {
        $this->training = $training;
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (
            ($notifiable->hasRole('Técnico') && TrainingConfig::first()->notification_technician_by_email == true) ||
            ($notifiable->hasRole('Jogador') && TrainingConfig::first()->notification_team_by_email == true)
        ) {
            return ['database', 'mail'];
        }

        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \App\Mail\NotificationTrainingMail
     */
    public function toMail($notifiable)
    {
        return (new NotificationTrainingMail($this->training, $notifiable))
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
        return [
            'training' => $this->training,
            'message' => trans('TrainingNotification.title_mail'),
        ];
    }
}
