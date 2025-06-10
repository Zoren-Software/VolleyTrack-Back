<?php

namespace App\Mail\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * @property \App\Models\User $user
 * @property string $tenant
 */
class Mail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var \App\Models\User
     */
    public $user;

    /**
     * @var string
     */
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
     * @return array<int, \Symfony\Component\Mime\Part\DataPart>
     */
    public function attachments(): array
    {
        return [];
    }
}
