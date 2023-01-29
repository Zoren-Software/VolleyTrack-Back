<?php

namespace App\Mail\Training;

use App\Models\Training;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ConfirmationNotificationTrainingMail extends Mail
{
    /**
     * The title of the mail.
     *
     * @var string
     */
    public $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Training $training, User $user)
    {
        parent::__construct($training, $user);
        $this->title = "$training->name - {$training->date_start->format('d/m/Y')} " . 
            trans('TrainingNotification.preposition_hours_from') . " " .
            "{$training->date_start->format('H:m')} " . trans('TrainingNotification.preposition_hours_to') .
            " {$training->date_end->format('H:m')}";
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
            ' - ' . trans('TrainingNotification.title_mail_confirmation') .
            ' - ' . $this->training->date_start->format('d/m/Y H:m') . 
            ' ' . trans('TrainingNotification.preposition_hours_to') . ' ' .
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
