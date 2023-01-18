<?php

namespace Tests\Unit\App\Mail\Training;

use Tests\TestCase;
use App\Mail\Training\Mail;
use App\Models\Training;
use App\Models\User;

class MailTest extends TestCase
{
    /**
     * A basic unit test example.
     * @test
     * @return void
     */
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
