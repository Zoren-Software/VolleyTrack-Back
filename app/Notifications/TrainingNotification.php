<?php

namespace App\Notifications;

use App\Mail\NotificationTrainingMail;
use Illuminate\Bus\Queueable;
use App\Models\Training;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
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
