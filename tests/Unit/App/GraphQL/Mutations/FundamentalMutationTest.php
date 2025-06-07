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
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('fundamentalProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function fundamental_make($data, $method)
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

    public static function fundamentalProvider()
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
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('fundamentalDeleteProvider')]
    #[\PHPUnit\Framework\Attributes\Test]
    public function fundamental_delete($data, $numberFind, $numberDelete)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $fundamental = $this->mock(Fundamental::class, function ($mock) use ($data, $numberFind, $numberDelete) {
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
        });

        $fundamentalMutation = new FundamentalMutation($fundamental);
        $fundamentalMutation->delete(
            null,
            [
                'id' => $data,
            ],
            $graphQLContext
        );
    }

    public static function fundamentalDeleteProvider()
    {
        return [
            'send array, success' => [
                'data' => [1],
                'numberFind' => 1,
                'numberDelete' => 1,
            ],
            'send multiple itens in array, success' => [
                'data' => [1, 2],
                'numberFind' => 1,
                'numberDelete' => 2,
            ],
            'send empty array, success' => [
                'data' => [],
                'numberFind' => 0,
                'numberDelete' => 0,
            ],
        ];
    }
}
