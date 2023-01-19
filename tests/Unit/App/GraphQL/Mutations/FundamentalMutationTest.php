<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\FundamentalMutation;
use App\Models\Fundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;

class FundamentalMutationTest extends TestCase
{
    /**
     * A basic unit test create and edit fundamental.
     *
     * @dataProvider fundamentalProvider
     * @test
     * @return void
     */
    public function fundamentalMake($data, $method)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);

        $fundamentalMock = $this->createMock(Fundamental::class);
        $fundamental = $this->getMockBuilder(Fundamental::class)
            ->addMethods([$method])
            ->getMock();

        $fundamental
            ->expects($this->any())
            ->method($method)
            ->willReturn($fundamentalMock);

        $fundamentalMutation = new FundamentalMutation($fundamental);
        $fundamentalMockReturn = $fundamentalMutation->make(
            null,
            $data,
            $graphQLContext
        );

        $this->assertEquals($fundamentalMock, $fundamentalMockReturn);
    }

    public function fundamentalProvider()
    {
        return [
            'send data create, success' => [
                'data' => [
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
                'method' => 'find',
            ],
        ];
    }

    /**
     * A basic unit test in delete position.
     *
     * @dataProvider positionDeleteProvider
     * @test
     * @return void
     */
    public function fundamentalDelete($data, $number)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $fundamental = $this->createMock(Fundamental::class);

        $fundamental->expects($this->exactly($number))
            ->method('deleteFundamental')
            ->willReturn($fundamental);

        $fundamentalMutation = new FundamentalMutation($fundamental);
        $fundamentalMutation->delete(
            null,
            [
                'id' => $data,
            ],
            $graphQLContext
        );
    }

    public function positionDeleteProvider()
    {
        return [
            'send array, success' => [
                [1],
                1,
            ],
            'send multiple itens in array, success' => [
                [1, 2, 3],
                3,
            ],
            'send empty array, success' => [
                [],
                0,
            ],
        ];
    }
}
