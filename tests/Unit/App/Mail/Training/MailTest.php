<?php

namespace Tests\Unit\App\Mail\Training;

use App\Mail\Training\Mail;
use App\Models\Training;
use App\Models\User;
use Tests\TestCase;

class MailTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function attachments()
    {
        $trainingMock = $this->createMock(Training::class);
        $userMock = $this->createMock(User::class);

        $mail = new Mail($trainingMock, $userMock);
        $attachments = $mail->attachments();

        $this->assertIsArray($attachments);
        $this->assertEmpty($attachments);
    }
}
