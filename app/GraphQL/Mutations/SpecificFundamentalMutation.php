<?php

namespace App\GraphQL\Mutations;

use App\Models\SpecificFundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class SpecificFundamentalMutation
{
    public function __construct(SpecificFundamental $specificFundamental)
    {
        $this->specificFundamental = $specificFundamental;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function create($rootValue, array $args, GraphQLContext $context)
    {
        $this->specificFundamental->name = $args['name'];
        $this->specificFundamental->user_id = $args['user_id'];
        $this->specificFundamental->save();

        if (isset($args['fundamental_id']) && $this->specificFundamental->fundamentals()) {
            $this->specificFundamental->fundamentals()->syncWithoutDetaching($args['fundamental_id']);
        }

        $this->specificFundamental->fundamentals;

        return $this->specificFundamental;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function edit($rootValue, array $args, GraphQLContext $context)
    {
        $this->specificFundamental->find($args['id']);
        $this->specificFundamental->name = $args['name'];
        $this->specificFundamental->user_id = $args['user_id'];
        $this->specificFundamental->save();

        if (isset($args['fundamental_id']) && $this->specificFundamental->fundamentals()) {
            $this->specificFundamental->fundamentals()->syncWithoutDetaching($args['fundamental_id']);
        }

        return $this->specificFundamental;
    }
}
