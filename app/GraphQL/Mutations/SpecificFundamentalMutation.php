<?php

namespace App\GraphQL\Mutations;

use App\Models\SpecificFundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class SpecificFundamentalMutation
{
    private SpecificFundamental $specificFundamental;

    public function __construct(SpecificFundamental $specificFundamental)
    {
        $this->specificFundamental = $specificFundamental;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context)
    {
        if (isset($args['id'])) {
            $this->specificFundamental = $this->specificFundamental->find(
                $args['id']
            );
            $this->specificFundamental->update(
                $args
            );
        } else {
            $this->specificFundamental = $this->specificFundamental->create(
                $args
            );
        }

        if (isset($args['fundamental_id'])) {
            $this->specificFundamental->fundamentals()->syncWithoutDetaching($args['fundamental_id']);
        }

        return $this->specificFundamental;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        $specificFundamentals = [];
        foreach ($args['id'] as $id) {
            $this->specificFundamental = $this->specificFundamental->findOrFail($id);
            $specificFundamentals[] = $this->specificFundamental;
            $this->specificFundamental->delete();
        }

        return $specificFundamentals;
    }
}
