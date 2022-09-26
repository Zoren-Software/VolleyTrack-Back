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
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        return $this->fundamental->create($args);
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $this->fundamental = $this->fundamental->find($args['id']);
        $this->fundamental->update($args);

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
            $this->fundamental = $this->fundamental->deleteFundamental($id);
            $fundamentals[] = $this->fundamental;
        }

        return $fundamentals;
    }
}
