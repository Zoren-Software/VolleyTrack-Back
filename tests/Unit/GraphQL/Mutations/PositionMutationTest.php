<?php

namespace Tests\Unit\GraphQL\Mutations;

use App\GraphQL\Mutations\PositionMutation;
use App\Models\Position;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use PHPUnit\Framework\TestCase;

class PositionMutationTest extends TestCase
{
    /**
     * A basic unit position create.
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
}
