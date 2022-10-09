<?php

namespace App\GraphQL\Mutations;

use App\Models\Training;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class TrainingMutation
{
    public function __construct(Training $training)
    {
        $this->training = $training;
    }

    /**
     * @param  null  $_
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

        return $this->training;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        $trainings = [];
        foreach ($args['id'] as $id) {
            $this->training = $this->training->deleteTeam($id);
            $trainings[] = $this->training;
        }

        return $trainings;
    }
}