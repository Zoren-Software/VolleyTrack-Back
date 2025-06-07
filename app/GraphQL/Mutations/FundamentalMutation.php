<?php

namespace App\GraphQL\Mutations;

use App\Models\Fundamental;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class FundamentalMutation
{
    private Fundamental $fundamental;

    public function __construct(Fundamental $fundamental)
    {
        $this->fundamental = $fundamental;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context): Fundamental
    {
        if (isset($args['id'])) {
            /** @var Fundamental $fundamental */
            $fundamental = Fundamental::findOrFail($args['id']);
            $fundamental->update($args);
            $this->fundamental = $fundamental;
        } else {
            $this->fundamental = Fundamental::create($args);
        }

        return $this->fundamental;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @return array<Fundamental>
     */
    public function delete($rootValue, array $args, GraphQLContext $context): array
    {
        /** @var array<int>|null $ids */
        $ids = isset($args['id']) && is_array($args['id']) ? $args['id'] : null;

        if ($ids === null) {
            throw new \RuntimeException('O campo "id" deve ser um array.');
        }

        $fundamentals = [];

        foreach ($ids as $id) {
            /** @var Fundamental $fundamental */
            $fundamental = Fundamental::findOrFail($id);
            $fundamentals[] = $fundamental;
            $fundamental->delete();
        }

        return $fundamentals;
    }
}
