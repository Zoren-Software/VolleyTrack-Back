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

    public Training $training;

    public ?ConfirmationTraining $confirmationTraining = null;

    /**
     * @var string
     */
    public $tenant;

    /**
     * Create a new notification instance.
     *
     *
     * @return void
     */
    public function __construct(Training $training, ?ConfirmationTraining $confirmationTraining = null)
    {
        $this->training = $training;
        $this->confirmationTraining = $confirmationTraining;

        $tenantId = tenant('id');

        if (!is_string($tenantId)) {
            throw new \RuntimeException('Tenant ID must be a string');
        }

        $this->tenant = $tenantId;

        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * NOTE - Todas as notificações Training agora são apenas via sistema (database).
     *
     * @param  mixed  $notifiable
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
