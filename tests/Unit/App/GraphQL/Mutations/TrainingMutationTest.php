<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\Models\Training;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\GraphQL\Mutations\TrainingMutation;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mockery\MockInterface;
use Tests\TestCase;

class TrainingMutationTest extends TestCase
{
    /**
     * A basic unit test create and edit training.
     *
     * @dataProvider trainingProvider
     *
     * @return void
     */
    public function test_training_make($data, $method)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $trainingMock = $this->mock(Training::class, function (MockInterface $mock) use ($data, $method) {
            $fundamentals = $this->createMock(BelongsToMany::class);
            $specificFundamentals = $this->createMock(BelongsToMany::class);

            if ($data['id']) {
                $mock->shouldReceive('find')
                    ->once()
                    ->with($data['id'])
                    ->andReturn($mock);
            }

            $mock->shouldReceive($method)
                ->with($data)
                ->once()
                ->andReturn($mock);

            if (isset($data['fundamental_id'])) {
                $mock->shouldReceive('fundamentals')
                    ->once()
                    ->andReturn($fundamentals);

                $mock->shouldReceive('syncWithoutDetaching')
                    ->with([$fundamentals]);
            }

            if (isset($data['specific_fundamental_id'])) {
                $mock->shouldReceive('specificFundamentals')
                    ->once()
                    ->andReturn($specificFundamentals);

                $mock->shouldReceive('syncWithoutDetaching')
                ->with([$specificFundamentals]);
            }
        });

        $specificFundamentalMutation = new TrainingMutation($trainingMock);
        $trainingMockReturn = $specificFundamentalMutation->make(
            null,
            $data,
            $graphQLContext
        );

        $this->assertEquals($trainingMock, $trainingMockReturn);
    }

    public function trainingProvider()
    {
        return [
            'send data create with all relationships, success' => [
                'data' => [
                    'id' => null,
                    'team_id' => 1,
                    'user_id' => 1,
                    'fundamental_id' => [1, 2],
                    'specific_fundamental_id' => [1, 2],
                    'name' => 'Training 1',
                    'description' => 'Training 1 description',
                    'date_start' => '2022-10-12 20:00:00',
                    'date_end' => '2022-10-12 22:00:00',
                ],
                'method' => 'create',
            ],
            'send data create with minimum parameters, success' => [
                'data' => [
                    'id' => null,
                    'team_id' => 1,
                    'user_id' => 1,
                    'name' => 'Training 1',
                    'description' => 'Training 1 description',
                    'date_start' => '2022-10-12 20:00:00',
                    'date_end' => '2022-10-12 22:00:00',
                ],
                'method' => 'create',
            ],
            'send data edit with all relationships, success' => [
                'data' => [
                    'id' => 1,
                    'team_id' => 1,
                    'user_id' => 1,
                    'fundamental_id' => [1, 2],
                    'specific_fundamental_id' => [1, 2],
                    'name' => 'Training 1',
                    'description' => 'Training 1 description',
                    'date_start' => '2022-10-12 20:00:00',
                    'date_end' => '2022-10-12 22:00:00',
                ],
                'method' => 'update',
            ],
            'send data edit with minimum parameters, success' => [
                'data' => [
                    'id' => 1,
                    'team_id' => 1,
                    'user_id' => 1,
                    'name' => 'Training 1',
                    'description' => 'Training 1 description',
                    'date_start' => '2022-10-12 20:00:00',
                    'date_end' => '2022-10-12 22:00:00',
                ],
                'method' => 'update',
            ],
        ];
    }

    /**
     * A basic unit test delete training.
     *
     * @dataProvider trainingDeleteProvider
     *
     * @return void
     */
    public function test_training_delete($data)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $trainingMock = $this->mock(Training::class, function (MockInterface $mock) use ($data) {
            $mock->shouldReceive('findOrFail')
                ->once()
                ->with(1)
                ->andReturn($mock);

            $mock->shouldReceive('delete')
                ->once()
                ->andReturn(true);
        });

        $specificFundamentalMutation = new TrainingMutation($trainingMock);

        $specificFundamentalMutation->delete(
            null,
            ['id' => [1]],
            $graphQLContext
        );
    }

    public function trainingDeleteProvider()
    {
        return [
            'send data delete, success' => [
                'data' => [
                    'id' => [1],
                ],
            ],
            'send data delete multiple trainings, success' => [
                'data' => [
                    'id' => [1, 2, 3],
                ],
            ],
        ];
    }
}
