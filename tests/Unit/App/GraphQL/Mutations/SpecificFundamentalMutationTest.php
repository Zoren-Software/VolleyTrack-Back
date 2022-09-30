<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\SpecificFundamentalMutation;
use App\Models\Fundamental;
use App\Models\SpecificFundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Tests\TestCase;

class SpecificFundamentalMutationTest extends TestCase
{
    /**
     * A basic unit test create and edit fundamental.
     *
     * @dataProvider specificFundamentalProvider
     *
     * @return void
     */
    public function test_specific_fundamental_make($data, $method)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);

        $fundamental = $this->createMock(Fundamental::class);
        $specificFundamentalMock = $this->createMock(SpecificFundamental::class);
        $specificFundamental = $this->getMockBuilder(SpecificFundamental::class)
            ->addMethods([$method, 'syncWithoutDetaching'])
            ->onlyMethods(['fundamentals'])
            ->getMock();

        $specificFundamental
            ->expects($this->any())
            ->method($method)
            ->willReturn($specificFundamentalMock);

        $specificFundamental->method('fundamentals')->willReturn([$fundamental]);


        $specificFundamentalMutation = new SpecificFundamentalMutation($specificFundamental);
        $specificFundamentalMockReturn = $specificFundamentalMutation->make(
            null,
            $data,
            $graphQLContext
        );

        $this->assertEquals($specificFundamentalMock, $specificFundamentalMockReturn);
    }


    public function specificFundamentalProvider()
    {
        return [
            'send data create, success' => [
                'data' => [
                    'id' => null,
                    'name' => 'Teste',
                    'fundamental_id' => 1,
                    'user_id' => 1,
                ],
                'method' => 'updateOrCreate',
            ],
            // 'send data edit, success' => [
            //     'data' => [
            //         'id' => 1,
            //         'name' => 'Teste',
            //         'fundamental_id' => 1,
            //         'user_id' => 1,
            //     ],
            //     'method' => 'find',
            // ],
        ];
    }

    /**
     * A basic unit test in delete specificFundamental.
     *
     * @dataProvider specificFundamentalDeleteProvider
     *
     * @return void
     */
    public function test_specific_fundamental_delete($data, $number)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $specificFundamental = $this->createMock(SpecificFundamental::class);

        $specificFundamental->expects($this->exactly($number))
            ->method('deleteSpecificFundamental')
            ->willReturn($specificFundamental);

        $specificFundamentalMutation = new SpecificFundamentalMutation($specificFundamental);
        $specificFundamentalMutation->delete(
            null,
            [
                'id' => $data,
            ],
            $graphQLContext
        );
    }

    public function specificFundamentalDeleteProvider()
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
