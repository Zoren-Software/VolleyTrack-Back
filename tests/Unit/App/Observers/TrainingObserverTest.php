<?php

namespace Tests\Unit\App\Observers;

use Tests\TestCase;
use App\Observers\TrainingObserver;
use App\Models\Training;

class TrainingObserverTest extends TestCase
{
    /**
     * Test created method
     * @test
     * @return void
     */
    public function created()
    {
        $trainingMock = $this->mock(Training::class, function ($mock) {
            $mock->shouldReceive('sendNotificationPlayers')
                ->once()
                ->andReturn(true);
        });

        $trainingObserver = new TrainingObserver();
        $trainingObserver->created($trainingMock);
    }

    /**
     * Test updated method
     * @test
     * @return void
     */
    public function updated()
    {
        $trainingMock = $this->mock(Training::class, function ($mock) {
            $mock->shouldReceive('sendNotificationPlayers')
                ->once()
                ->andReturn(true);
        });

        $trainingObserver = new TrainingObserver();
        $trainingObserver->updated($trainingMock);
    }
}
