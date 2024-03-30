<?php

namespace Tests\Unit\App\Rules;

use App\Models\ConfirmationTraining;
use App\Rules\CheckPlayerIsInTraining;
use Mockery\MockInterface;
use Tests\TestCase;

class CheckPlayerIsInTrainingTest extends TestCase
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
        $playerId = 1;
        $trainingId = 1;
        $confirmationTraining = $this->createMock(ConfirmationTraining::class);

        $permissionAssignment = new CheckPlayerIsInTraining($playerId, $trainingId, $confirmationTraining);
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
        $playerId = 1;
        $trainingId = 1;
        $confirmationTrainingMock = $this->mock(
            ConfirmationTraining::class,
            function (MockInterface $mock) use ($passes) {
                $mock->shouldReceive('where')
                    ->once()
                    ->with('player_id', 1)
                    ->andReturnSelf();
                $mock->shouldReceive('where')
                    ->once()
                    ->with('training_id', 1)
                    ->andReturnSelf();
                $mock->shouldReceive('first')
                    ->once()
                    ->andReturn($passes);
            }
        );

        $permissionAssignment = new CheckPlayerIsInTraining($playerId, $trainingId, $confirmationTrainingMock);
        $this->assertEquals($permissionAssignment->passes('player_id', $playerId), $passes);
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
