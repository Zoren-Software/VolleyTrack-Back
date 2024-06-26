<?php

namespace App\Notifications\Training;

use App\Models\ConfirmationTraining;
use App\Models\Training;
use App\Models\TrainingConfig;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification as IlluminateNotification;

class Notification extends IlluminateNotification implements ShouldQueue
{
    use Queueable;

    public Training $training;

    public $confirmationTraining;

    public $tenant;

    /**
     * Create a new notification instance.
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(
        User $notifiable,
        $mock = 'notMock',
        $notificationTechnicianByEmail = false,
        $notificationTeamByEmail = false
    ) {
        $this->queue = 'emails';

        $notificationTechnicianByEmail =
            $mock == 'notMock'
                // @codeCoverageIgnoreStart
                ? TrainingConfig::first()->notification_technician_by_email
                // @codeCoverageIgnoreEnd
                : $notificationTechnicianByEmail;

        $notificationTeamByEmail =
            $mock == 'notMock'
            // @codeCoverageIgnoreStart
            ? TrainingConfig::first()->notification_team_by_email
            // @codeCoverageIgnoreEnd
            : $notificationTeamByEmail;

        if (
            $notificationTechnicianByEmail || $notificationTeamByEmail
        ) {
            return ['database', 'mail'];
        }

        return ['database'];
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array<int, string>
     */
    public function tags(): array
    {
        return ['tenant:' . $this->tenant];
    }
}
