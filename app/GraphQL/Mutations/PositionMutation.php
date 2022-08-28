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
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        $this->position->name = $args['name'];
        $this->position->user_id = $args['user_id'];
        $this->position->save();

        return $this->position;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $this->position->find($args['id']);
        $this->position->name = $args['name'];
        $this->position->user_id = $args['user_id'];
        $this->position->save();

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
