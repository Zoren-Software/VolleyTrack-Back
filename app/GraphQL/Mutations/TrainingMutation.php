<?php

namespace App\GraphQL\Mutations;

use App\Models\Training;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class TrainingMutation
{
    private Training $training;

    public function __construct(Training $training)
    {
        $this->training = $training;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context): Training
    {
        if (isset($args['id'])) {
            /** @var Training $training */
            $training = Training::find($args['id']);
            $this->training = $training;
            $this->training->update($args);
        } else {
            $this->training = $this->training->create($args);
        }

        if (isset($args['fundamental_id'])) {
            $fundamentalIds = is_array($args['fundamental_id']) ? $args['fundamental_id'] : [$args['fundamental_id']];
            /** @var array<int|string> $fundamentalIds */
            $this->training->fundamentals()->syncWithoutDetaching($fundamentalIds);
        }

        if (isset($args['specific_fundamental_id'])) {
            $specificFundamentalIds = is_array($args['specific_fundamental_id']) ? $args['specific_fundamental_id'] : [$args['specific_fundamental_id']];
            /** @var array<int|string> $specificFundamentalIds */
            $this->training->specificFundamentals()->syncWithoutDetaching($specificFundamentalIds);
        }

        return $this->training;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @return array<Training>
     */
    public function delete($rootValue, array $args, GraphQLContext $context): array
    {
        /** @var array<int|string> $ids */
        $ids = is_array($args['id']) ? $args['id'] : [];

        $trainings = [];

        foreach ($ids as $id) {
            /** @var Training $training */
            $training = Training::findOrFail($id);
            $this->training = $training;
            $trainings[] = $this->training;
            $this->training->delete();
        }

        return $trainings;
    }
}
