<?php

namespace App\Notifications\Training;

use App\Models\ConfirmationTraining;
use App\Models\Training;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as IlluminateNotification;

class Notification extends IlluminateNotification implements ShouldQueue
{
    use Queueable;

    /**
     * @var Training
     */
    public Training $training;

    /**
     * @var ConfirmationTraining
     */
    public ConfirmationTraining $confirmationTraining;

    /**
     * @var int
     */
    public $tenant;

    /**
     * Create a new notification instance.
     *
     * @param Training $training
     * @param ConfirmationTraining|null $confirmationTraining
     * 
     * @return void
     */
    public function __construct(Training $training, ?ConfirmationTraining $confirmationTraining = null)
    {
        $this->training = $training;
        $this->confirmationTraining = $confirmationTraining;
        $this->tenant = tenant('id');
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * NOTE - Todas as notificações Training agora são apenas via sistema (database).
     *
     * @param  mixed  $notifiable
     * 
     * @return array<string>
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<string>
     */
    public function tags(): array
    {
        return ['tenant:' . $this->tenant];
    }
}
