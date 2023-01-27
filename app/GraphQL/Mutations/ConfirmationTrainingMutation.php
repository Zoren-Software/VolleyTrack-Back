<?php

namespace App\GraphQL\Mutations;

use App\Models\ConfirmationTraining;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class ConfirmationTrainingMutation
{
    private ConfirmationTraining $confirmationTraining;

    public function __construct(ConfirmationTraining $confirmationTraining)
    {
        $this->confirmationTraining = $confirmationTraining;
    }

    public function confirmTraining($rootValue, array $args, GraphQLContext $context): ConfirmationTraining
    {
        if(isset($args['id'])) {
            $this->confirmationTraining = $this->confirmationTraining->find($args['id']);
        } else if(isset($args['training_id']) && isset($args['player_id'])) {
            $this->confirmationTraining = $this->confirmationTraining
                ->where('training_id', $args['training_id'])
                ->where('player_id', $args['player_id'])->first();
        }

        $this->confirmationTraining->status = $args['status'];
        $this->confirmationTraining->save();

        return $this->confirmationTraining;
    }
}
