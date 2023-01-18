<?php

namespace App\Mail\Training;

use App\Models\Training;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
