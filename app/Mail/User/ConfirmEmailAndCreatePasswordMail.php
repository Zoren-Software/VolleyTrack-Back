<?php

namespace App\Mail\User;

use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ConfirmEmailAndCreatePasswordMail extends Mail
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
    public function __construct(User $user, String $tenant)
    {
        parent::__construct($user, $tenant);
        $this->title = "Confirme seu e-mail e crie sua senha";
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
            ' - ' . 'Confirme seu e-mail e crie sua senha'
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
            markdown: 'emails.user.confirm-email-and-create-password',
        );
    }
}
