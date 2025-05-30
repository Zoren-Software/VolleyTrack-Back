<?php

namespace App\GraphQL\Mutations;

use App\Models\SpecificFundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class SpecificFundamentalMutation
{
    private SpecificFundamental $specificFundamental;

    /**
     * @param SpecificFundamental $specificFundamental
     */
    public function __construct(SpecificFundamental $specificFundamental)
    {
        $this->specificFundamental = $specificFundamental;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return SpecificFundamental
     */
    public function make($rootValue, array $args, GraphQLContext $context): SpecificFundamental
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
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return array<SpecificFundamental>
     */
    public function delete($rootValue, array $args, GraphQLContext $context): array
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
