<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\ConfirmationTrainingMutation;
use App\Models\ConfirmationTraining;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;

class ConfirmationTrainingMutationTest extends TestCase
{
    /**
     * A basic unit test confirmTraining.
     *
     * @test
     *
     * @dataProvider confirmationTrainingProvider
     * 
     * @return void
     */
    public function confirmTraining($data)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);

        $confirmationTrainingMock = $this->mock(ConfirmationTraining::class, function ($mock) use ($data) {
            
            if(isset($data['id'])) {
                $mock->shouldReceive('find')
                    ->once()
                    ->with(1)
                    ->andReturn($mock);
            }

            if(isset($data['training_id']) && isset($data['player_id'])) {
                $mock->shouldReceive('where')
                    ->once()
                    ->with('training_id', 1)
                    ->andReturn($mock);

                $mock->shouldReceive('where')
                    ->once()
                    ->with('player_id', 1)
                    ->andReturn($mock);

                $mock->shouldReceive('first')
                    ->once()
                    ->andReturn($mock);
            }

            $mock->shouldReceive('setAttribute')
                ->once()
                ->with('status', 'confirmed');

            $mock->shouldReceive('save')
                ->once()
                ->andReturn(true);

        });

        $confirmationTrainingMutation = new ConfirmationTrainingMutation($confirmationTrainingMock);
        $confirmationTrainingMockReturn = $confirmationTrainingMutation->confirmTraining(
            null,
            $data,
            $graphQLContext
        );

        $this->assertEquals($confirmationTrainingMock, $confirmationTrainingMockReturn);
    }

    public function confirmationTrainingProvider() {
        return [
            'sending single id parameter as reference, success' => [
                'data' => [
                    'id' => 1,
                    'status' => 'confirmed',
                ],
            ],
            'sending player and training parameters as a reference, success' => [
                'data' => [
                    'training_id' => 1,
                    'player_id' => 1,
                    'status' => 'confirmed',
                ],
            ],
        ];
    }
}
