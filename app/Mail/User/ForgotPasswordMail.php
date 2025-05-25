<?php

namespace App\Mail\User;

use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ForgotPasswordMail extends Mail
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
    public function __construct(User $user, string $tenant)
    {
        parent::__construct($user, $tenant);

        $this->title = 'Recuperação de senha';
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: config('app.name') .
            ' - ' . 'Recuperação de senha'
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
            markdown: 'emails.user.forgot-password-email',
        );
    }
}
