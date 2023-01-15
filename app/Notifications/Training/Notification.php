<?php

namespace App\Notifications\Training;

use App\Models\Training;
use App\Models\TrainingConfig;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as IlluminateNotification;

class Notification extends IlluminateNotification implements ShouldQueue
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
            ($notifiable->hasRole('Técnico') && TrainingConfig::first()->notification_technician_by_email) ||
            ($notifiable->hasRole('Jogador') && TrainingConfig::first()->notification_team_by_email)
        ) {
            return ['database', 'mail'];
        }

        return ['database'];
    }
}
