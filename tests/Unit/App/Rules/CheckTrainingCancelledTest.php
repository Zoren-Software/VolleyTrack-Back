<?php

namespace Tests\Unit\App\Rules;

use App\Models\Training;
use App\Rules\CheckTrainingCancelled;
use Mockery\MockInterface;
use Tests\TestCase;

class CheckTrainingCancelledTest extends TestCase
{
    /**
     * A basic unit test message.
     *
     * @test
     *
     * @return void
     */
    public function message()
    {
        $trainingId = 1;
        $training = $this->createMock(Training::class);

        $permissionAssignment = new CheckTrainingCancelled($trainingId, $training);
        $this->assertIsString($permissionAssignment->message());
    }

    /**
     * A basic unit test passes.
     *
     * @test
     *
     * @dataProvider passesProvider
     *
     * @return void
     */
    public function passes($passes)
    {
        $trainingId = 1;
        $trainingMock = $this->mock(
            Training::class,
            function (MockInterface $mock) use ($passes) {
                $mock->shouldReceive('getAttribute')
                    ->once()
                    ->with('status')
                    ->andReturn($passes);

                $mock->shouldReceive('find')
                    ->once()
                    ->with(1)
                    ->andReturnSelf();
            }
        );

        $permissionAssignment = new CheckTrainingCancelled($trainingId, $trainingMock);
        $this->assertEquals($permissionAssignment->passes('player_id', $trainingId), $passes);
    }

    public static function passesProvider()
    {
        return [
            'when there is player related to training' => [
                'passes' => true,
            ],
            'if there is no player related to training' => [
                'passes' => false,
            ],
        ];
    }
}
