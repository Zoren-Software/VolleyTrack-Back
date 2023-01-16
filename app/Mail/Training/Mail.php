<?php

namespace App\Mail\Training;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Training;
use App\Models\User;

class Mail extends Mailable
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
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
