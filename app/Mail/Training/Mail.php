<?php

namespace App\Mail\Training;

use App\Models\Training;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * @property \App\Models\Training $training
 * @property \App\Models\User $user
 */
class Mail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var \App\Models\Training
     */
    public $training;

    /**
     * @var \App\Models\User
     */
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
     * @return array<int, \Symfony\Component\Mime\Part\DataPart>
     */
    public function attachments()
    {
        return [];
    }
}
