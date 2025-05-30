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
            $this->position = $this->position->find($args['id']);
            $this->position->update($args);
        } else {
            $this->position = $this->position->create($args);
        }

        return $this->position;
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
            $this->position = $this->position->findOrFail($id);
            $positions[] = $this->position;
            $this->position->delete();
        }

        return $positions;
    }
}
