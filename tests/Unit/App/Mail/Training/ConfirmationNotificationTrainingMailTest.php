<?php

namespace Tests\Unit\App\Mail\Training;

use App\Mail\Training\ConfirmationTrainingMail;
use App\Models\Training;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Tests\TestCase;

class ConfirmationTrainingMailTest extends TestCase
{
    public $dateStart = '2020-01-01 00:00:00';

    /**
     * A method to test the envelope.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function envelope()
    {
        $trainingMock = $this->mock(Training::class, function ($mock) {
            $mock->shouldReceive('getAttribute')->with('date_start')->andReturn(
                \Carbon\Carbon::parse($this->dateStart)
            );
            $mock->shouldReceive('getAttribute')->with('date_end')->andReturn(
                \Carbon\Carbon::parse($this->dateStart)
            );
            $mock->shouldReceive('getAttribute')->with('name')->andReturn(
                'Test name'
            );
        });

        $userMock = $this->createMock(User::class);
        $mail = new ConfirmationTrainingMail($trainingMock, $userMock);
        $envelope = $mail->envelope();

        $this->assertInstanceOf(Envelope::class, $envelope);
    }

    /**
     * A method to test the content.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function content()
    {
        $trainingMock = $this->mock(Training::class, function ($mock) {
            $mock->shouldReceive('getAttribute')->with('date_start')->andReturn(
                \Carbon\Carbon::parse($this->dateStart)
            );
            $mock->shouldReceive('getAttribute')->with('date_end')->andReturn(
                \Carbon\Carbon::parse($this->dateStart)
            );
            $mock->shouldReceive('getAttribute')->with('name')->andReturn(
                'Test name'
            );
        });

        $userMock = $this->createMock(User::class);
        $mail = new ConfirmationTrainingMail($trainingMock, $userMock);
        $content = $mail->content();

        $this->assertInstanceOf(Content::class, $content);
    }
}
