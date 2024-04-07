<?php

namespace App\Mail\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Mail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $user;

    public $tenant;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $tenant)
    {
        $this->user = $user;
        $this->tenant = $tenant;
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
