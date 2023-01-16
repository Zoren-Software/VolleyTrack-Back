<?php

namespace App\Mail\Training;

use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ConfirmationNotificationTrainingMail extends Mail
{
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: env('APP_NAME') .
            ' - ' . trans('TrainingNotification.title_mail_confirmation') .
            ' - ' . $this->training->date_start->format('d/m/Y H:m') .
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
            markdown: 'emails.training.confirmation-notification',
        );
    }
}
