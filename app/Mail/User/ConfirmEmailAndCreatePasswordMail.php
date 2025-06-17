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
     * @var bool
     */
    private $admin;

    /**
     * Create a new message instance.
     *
     * @param  bool  $admin
     * @return void
     */
    public function __construct(User $user, string $tenant, $admin = false)
    {
        parent::__construct($user, $tenant);

        if ($this->admin === false) {
            $this->title = 'Confirme seu e-mail e crie sua senha';
        } elseif ($this->admin === true) {
            $this->title = 'Dados de acesso inicial ao sistema';
        }

        $this->admin = $admin;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $appName = config('app.name');

        return new Envelope(
            subject: (is_string($appName) ? $appName : '') . ' - Confirme seu e-mail e crie sua senha'
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        if ($this->admin === false) {
            return new Content(
                markdown: 'emails.user.confirm-email-and-create-password',
            );
        }

        return new Content(
            markdown: 'emails.user.tenant-registration-email',
        );
    }
}
