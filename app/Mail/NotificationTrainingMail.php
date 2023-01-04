<?php

namespace App\Mail;

use App\Models\Training;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificationTrainingMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $training;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Training $training, User $user)
    {
        $this->training = $training;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: env('APP_NAME') .
            ' - ' . trans('TrainingNotification.title_mail') .
            ' - ' . $this->training->date_start->format('d/m/Y H:m') .
            ' ' . trans('TrainingNotification.title_mail') . ' ' .
            $this->training->date_end->format('H:m')
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.training.notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
