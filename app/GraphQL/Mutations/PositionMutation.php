<?php

namespace App\GraphQL\Mutations;

use App\Models\Position;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class PositionMutation
{
    private $position;

    public function __construct(Position $position)
    {
        $this->position = $position;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context)
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
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        $positions = [];
        foreach ($args['id'] as $id) {
            $this->position = $this->position->deletePosition($id);
            $positions[] = $this->position;
        }

        return $positions;
    }
}
