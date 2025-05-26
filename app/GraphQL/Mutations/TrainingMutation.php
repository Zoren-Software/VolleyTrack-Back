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
    public function make($rootValue, array $args, GraphQLContext $context)
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
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
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
