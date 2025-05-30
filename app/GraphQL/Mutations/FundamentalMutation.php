<?php

namespace App\GraphQL\Mutations;

use App\Models\Fundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class FundamentalMutation
{
    private Fundamental $fundamental;

    /**
     * @param Fundamental $fundamental
     */
    public function __construct(Fundamental $fundamental)
    {
        $this->fundamental = $fundamental;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return Fundamental
     */
    public function make($rootValue, array $args, GraphQLContext $context): Fundamental
    {
        if (isset($args['id'])) {
            $this->fundamental = $this->fundamental->find($args['id']);
            $this->fundamental->update($args);
        } else {
            $this->fundamental = $this->fundamental->create($args);
        }

        return $this->fundamental;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return array<Fundamental>
     */
    public function delete($rootValue, array $args, GraphQLContext $context): array
    {
        $fundamentals = [];
        foreach ($args['id'] as $id) {
            $this->fundamental = $this->fundamental->findOrFail($id);
            $fundamentals[] = $this->fundamental;
            $this->fundamental->delete();
        }

        return $fundamentals;
    }
}
