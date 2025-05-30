<?php

namespace App\GraphQL\Mutations;

use App\Models\Training;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class TrainingMutation
{
    private Training $training;

    /**
     * @param Training $training
     */
    public function __construct(Training $training)
    {
        $this->training = $training;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return Training
     */
    public function make($rootValue, array $args, GraphQLContext $context): Training
    {
        if (isset($args['id'])) {
            $this->training = $this->training->find($args['id']);
            $this->training->update($args);
        } else {
            $this->training = $this->training->create($args);
        }

        if (isset($args['fundamental_id'])) {
            $this->training->fundamentals()->syncWithoutDetaching($args['fundamental_id']);
        }

        if (isset($args['specific_fundamental_id'])) {
            $this->training->specificFundamentals()->syncWithoutDetaching($args['specific_fundamental_id']);
        }

        return $this->training;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return array<Training>
     */
    public function delete($rootValue, array $args, GraphQLContext $context): array
    {
        $trainings = [];
        foreach ($args['id'] as $id) {
            $this->training = $this->training->findOrFail($id);
            $trainings[] = $this->training;
            $this->training->delete();
        }

        return $trainings;
    }
}
