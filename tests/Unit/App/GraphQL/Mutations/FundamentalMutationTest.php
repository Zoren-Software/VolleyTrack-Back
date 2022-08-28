<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\FundamentalMutation;
use App\Models\Fundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use PHPUnit\Framework\TestCase;

class FundamentalMutationTest extends TestCase
{
    /**
     * A basic unit test create fundamental.
     *
     * @return void
     */
    public function test_fundamental_create()
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $fundamental = $this->createMock(Fundamental::class);

        $fundamental->expects($this->once())
        ->method('save');

        $fundamentalMutation = new FundamentalMutation($fundamental);
        $fundamentalMutation->create(null, [
            'name' => 'Teste',
            'user_id' => 1,
        ], $graphQLContext);
    }

    /**
     * A basic unit test create fundamental.
     *
     * @return void
     */
    public function test_fundamental_edit()
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $fundamental = $this->createMock(Fundamental::class);

        $fundamental->expects($this->once())
        ->method('save');

        $fundamentalMutation = new FundamentalMutation($fundamental);
        $fundamentalMutation->edit(null, [
            'id' => 1,
            'name' => 'Teste',
            'user_id' => 1,
        ], $graphQLContext);
    }

        /**
     * A basic unit test in delete position.
     * @dataProvider positionDeleteProvider
     *
     * @return void
     */
    public function test_fundamental_delete($data, $number)
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
            ]
        ];
    }
}
