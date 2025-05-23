<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\TrainingMutation;
use App\Models\Training;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mockery\MockInterface;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;

class TrainingMutationTest extends TestCase
{
    public static $trainingName = 'Training 1';

    public static $descriptionText = ' description';

    public static $dateStart = '2022-10-12 20:00:00';

    public static $dateEnd = '2022-10-12 22:00:00';

    /**
     * A basic unit test create and edit training.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('trainingProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function training_make($data, $method)
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

    public static function trainingProvider()
    {
        return [
            'send data create with all relationships, success' => [
                'data' => [
                    'id' => null,
                    'team_id' => 1,
                    'user_id' => 1,
                    'fundamental_id' => [1, 2],
                    'specific_fundamental_id' => [1, 2],
                    'name' => self::$trainingName,
                    'description' => self::$trainingName . self::$descriptionText,
                    'date_start' => self::$dateStart,
                    'date_end' => self::$dateEnd,
                ],
                'method' => 'create',
            ],
            'send data create with minimum parameters, success' => [
                'data' => [
                    'id' => null,
                    'team_id' => 1,
                    'user_id' => 1,
                    'name' => self::$trainingName,
                    'description' => self::$trainingName . self::$descriptionText,
                    'date_start' => self::$dateStart,
                    'date_end' => self::$dateEnd,
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
                    'name' => self::$trainingName,
                    'description' => self::$trainingName . self::$descriptionText,
                    'date_start' => self::$dateStart,
                    'date_end' => self::$dateEnd,
                ],
                'method' => 'update',
            ],
            'send data edit with minimum parameters, success' => [
                'data' => [
                    'id' => 1,
                    'team_id' => 1,
                    'user_id' => 1,
                    'name' => self::$trainingName,
                    'description' => self::$trainingName . self::$descriptionText,
                    'date_start' => self::$dateStart,
                    'date_end' => self::$dateEnd,
                ],
                'method' => 'update',
            ],
        ];
    }

    /**
     * A basic unit test delete training.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\DataProvider('trainingDeleteProvider')]
    public function training_delete($data, $numberFind, $numberDelete)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $trainingMock = $this->mock(
            Training::class,
            function (MockInterface $mock) use ($data, $numberFind, $numberDelete) {
                $mock->shouldReceive('findOrFail')
                    ->times($numberFind)
                    ->with(1)
                    ->andReturn($mock);

                if (count($data) > 1) {
                    $mock->shouldReceive('findOrFail')
                        ->times($numberFind)
                        ->with(2)
                        ->andReturn($mock);
                }

                $mock->shouldReceive('delete')
                    ->times($numberDelete)
                    ->andReturn(true);
            }
        );

        $specificFundamentalMutation = new TrainingMutation($trainingMock);

        $specificFundamentalMutation->delete(
            null,
            [
                'id' => $data,
            ],
            $graphQLContext
        );
    }

    public static function trainingDeleteProvider()
    {
        return [
            'send data delete, success' => [
                'data' => [1],
                'numberFind' => 1,
                'numberDelete' => 1,
            ],
            'send data delete multiple trainings, success' => [
                'data' => [1, 2],
                'numberFind' => 1,
                'numberDelete' => 2,
            ],
            'send data delete no items, success' => [
                'data' => [],
                'numberFind' => 0,
                'numberDelete' => 0,
            ],
        ];
    }
}
