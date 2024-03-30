<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\PositionMutation;
use App\Models\Position;
use Mockery\MockInterface;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;

class PositionMutationTest extends TestCase
{
    /**
     * A basic unit test create and edit position.
     *
     * @dataProvider positionProvider
     *
     * @test
     *
     * @return void
     */
    public function positionMake($data, $method)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $positionMock = $this->mock(Position::class, function (MockInterface $mock) use ($data, $method) {
            if ($data['id']) {
                $mock->shouldReceive('find')
                    ->once()
                    ->with($data['id'])
                    ->andReturn($mock);
            }

            $mock->shouldReceive($method)->with($data)->once()->andReturn($mock);
        });

        $specificFundamentalMutation = new PositionMutation($positionMock);
        $positionMockReturn = $specificFundamentalMutation->make(
            null,
            $data,
            $graphQLContext
        );

        $this->assertEquals($positionMock, $positionMockReturn);
    }

    public static function positionProvider()
    {
        return [
            'send data create, success' => [
                'data' => [
                    'id' => null,
                    'name' => 'Teste',
                    'user_id' => 1,
                ],
                'method' => 'create',
            ],
            'send data edit, success' => [
                'data' => [
                    'id' => 1,
                    'name' => 'Teste',
                    'user_id' => 1,
                ],
                'method' => 'update',
            ],
        ];
    }

    /**
     * A basic unit test in delete position.
     *
     * @dataProvider positionDeleteProvider
     *
     * @test
     *
     * @return void
     */
    public function positionDelete($data, $numberFind, $numberDelete)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $position = $this->mock(
            Position::class,
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

        $positionMutation = new PositionMutation($position);
        $positionMutation->delete(
            null,
            [
                'id' => $data,
            ],
            $graphQLContext
        );
    }

    public static function positionDeleteProvider()
    {
        return [
            'send array, success' => [
                'data' => [1],
                'numberFind' => 1,
                'numberDelete' => 1,
            ],
            'send data delete multiples positions, success' => [
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
