<?php

namespace App\GraphQL\Mutations;

use App\Models\TrainingConfig;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class TrainingConfigMutation
{
    private TrainingConfig $trainingConfig;

    public function __construct(TrainingConfig $trainingConfig)
    {
        $this->trainingConfig = $trainingConfig;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context): TrainingConfig
    {
        $this->trainingConfig = $this->trainingConfig->findOrFail(1);
        $this->trainingConfig->update($args);

        return $this->trainingConfig;
    }
}
