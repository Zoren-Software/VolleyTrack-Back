<?php

namespace App\GraphQL\Mutations;

use App\Models\Fundamental;

final class FundamentalMutation
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        $fundamental = new Fundamental();
        $fundamental->name = $args['name'];
        $fundamental->user_id = $args['user_id'];
        $fundamental->save();

        return $fundamental;
    }

        /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $fundamental = Fundamental::find($args['id']);
        $fundamental->name = $args['name'];
        $fundamental->user_id = $args['user_id'];
        $fundamental->save();

        return $fundamental;
    }
}
