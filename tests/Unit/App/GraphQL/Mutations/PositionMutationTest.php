<?php

namespace Tests\Unit\App\GraphQL\Mutations;

use App\GraphQL\Mutations\PositionMutation;
use App\Models\Position;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use PHPUnit\Framework\TestCase;

class PositionMutationTest extends TestCase
{
    /**
     * A basic unit position create.
     *
     * @return void
     */
    public function test_position_create()
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $position = $this->createMock(Position::class);

        $position->expects($this->once())
        ->method('save');

        $positionMutation = new PositionMutation($position);
        $positionMutation->create(null, [
            'name' => 'Teste',
            'user_id' => 1,
        ], $graphQLContext);
    }

    /**
     * A basic unit position edit.
     *
     * @return void
     */
    public function test_position_edit()
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $position = $this->createMock(Position::class);

        $position->expects($this->once())
        ->method('save');

        $positionMutation = new PositionMutation($position);
        $positionMutation->edit(null, [
            'id' => 1,
            'name' => 'Teste',
            'user_id' => 1,
        ], $graphQLContext);
    }

    /**
     * A basic unit test in delete position.
     *
     * @dataProvider positionDeleteProvider
     *
     * @return void
     */
    public function test_position_delete($data, $number)
    {
        $graphQLContext = $this->createMock(GraphQLContext::class);
        $position = $this->createMock(Position::class);

        $position->expects($this->exactly($number))
            ->method('deletePosition')
            ->willReturn($position);

        $positionMutation = new PositionMutation($position);
        $positionMutation->delete(
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
