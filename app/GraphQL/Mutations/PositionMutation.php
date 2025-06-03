<?php

namespace App\GraphQL\Mutations;

use App\Models\Position;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class PositionMutation
{
    private Position $position;

    /**
     * @param Position $position
     */
    public function __construct(Position $position)
    {
        $this->position = $position;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return Position
     */
    public function make($rootValue, array $args, GraphQLContext $context): Position
    {
        if (isset($args['id'])) {
            $found = $this->position->find($args['id']);

            if (! $found instanceof Position) {
                throw new \Exception("Position not found.");
            }

            $found->update($args);
            return $found;
        }

        return $this->position->create($args);
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return array<Position>
     */
    public function delete($rootValue, array $args, GraphQLContext $context): array
    {
        $positions = [];

        foreach ($args['id'] as $id) {
            /** @var Position $position */
            $position = Position::findOrFail($id);
            $positions[] = $position;
            $position->delete();
        }

        return $positions;
    }
}
