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
        $this->fundamental->name = $args['name'];
        $this->fundamental->user_id = $args['user_id'];
        $this->fundamental->save();

        return $this->fundamental;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $this->fundamental->findOrFail($args['id']);
        $this->fundamental->name = $args['name'];
        $this->fundamental->user_id = $args['user_id'];
        $this->fundamental->save();

        return $this->fundamental;
    }
}
