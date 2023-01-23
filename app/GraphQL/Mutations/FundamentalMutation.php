<?php

namespace App\GraphQL\Mutations;

use App\Models\Fundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class FundamentalMutation
{
    public function __construct(Fundamental $fundamental)
    {
        $this->fundamental = $fundamental;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context)
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
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
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
