<?php

namespace Tests\Unit\App\Mail\Training;

use Tests\TestCase;
use App\Mail\Training\ConfirmationNotificationTrainingMail;
use App\Models\Training;
use App\Models\User;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class ConfirmationNotificationTrainingMailTest extends TestCase
{
    /**
     * A method to test the envelope.
     * @test
     * @return void
     */
    public function envelope()
    {
        $trainingMock = $this->mock(Training::class, function ($mock) {
            $mock->shouldReceive('getAttribute')->with('date_start')->andReturn(
                // retornar a data de início do treino em formato Carbon
                \Carbon\Carbon::parse('2020-01-01 00:00:00')
            );
            $mock->shouldReceive('getAttribute')->with('date_end')->andReturn(
                // retornar a data de início do treino em formato Carbon
                \Carbon\Carbon::parse('2020-01-01 00:00:00')
            );
        });

        $userMock = $this->createMock(User::class);
        $mail = new ConfirmationNotificationTrainingMail($trainingMock, $userMock);
        $envelope = $mail->envelope();

        $this->assertInstanceOf(Envelope::class, $envelope);
    }

    /**
     * A method to test the content.
     * @test
     * @return void
     */
    public function content()
    {
        $trainingMock = $this->createMock(Training::class);

        $userMock = $this->createMock(User::class);
        $mail = new ConfirmationNotificationTrainingMail($trainingMock, $userMock);
        $content = $mail->content();

        $this->assertInstanceOf(Content::class, $content);
    }
}
