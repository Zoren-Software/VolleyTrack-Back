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
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context): SpecificFundamental
    {
        if (isset($args['id'])) {
            $found = $this->specificFundamental->find($args['id']);

            if (!$found instanceof SpecificFundamental) {
                throw new \Exception('SpecificFundamental not found.');
            }

            $this->specificFundamental = $found;
            $this->specificFundamental->update($args);
        } else {
            $this->specificFundamental = $this->specificFundamental->create($args);
        }

        if (isset($args['fundamental_id'])) {
            // @phpstan-ignore-next-line
            $this->specificFundamental->fundamentals()->syncWithoutDetaching($args['fundamental_id']);
        }

        return $this->specificFundamental;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @return array<SpecificFundamental>
     */
    public function delete($rootValue, array $args, GraphQLContext $context): array
    {
        /** @var array<int>|null $ids */
        $ids = isset($args['id']) && is_array($args['id']) ? $args['id'] : null;

        if ($ids === null) {
            throw new \RuntimeException('Parâmetro "id" inválido ou ausente.');
        }

        $specificFundamentals = [];

        foreach ($ids as $id) {
            /** @var SpecificFundamental $specificFundamental */
            $specificFundamental = SpecificFundamental::findOrFail($id);
            $specificFundamentals[] = $specificFundamental;
            $specificFundamental->delete();
        }

        return $specificFundamentals;
    }
}
