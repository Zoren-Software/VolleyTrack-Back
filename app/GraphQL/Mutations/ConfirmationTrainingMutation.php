<?php

namespace App\GraphQL\Mutations;

use App\Models\ConfirmationTraining;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class ConfirmationTrainingMutation
{
    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function confirmTraining($rootValue, array $args, GraphQLContext $context): ConfirmationTraining
    {
        return $this->confirm('status', $args);
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function confirmPresence($rootValue, array $args, GraphQLContext $context): ConfirmationTraining
    {
        return $this->confirm('presence', $args);
    }

    /**
     * @param  array<string, mixed>  $args
     */
    public function confirm(string $parameterSave, array $args): ConfirmationTraining
    {
        $confirmationTraining = null;

        if (isset($args['id'])) {
            $confirmationTraining = ConfirmationTraining::find($args['id']);
        } elseif (isset($args['training_id'], $args['player_id'])) {
            $confirmationTraining = ConfirmationTraining::where('training_id', $args['training_id'])
                ->where('player_id', $args['player_id'])
                ->first();
        }

        if (!$confirmationTraining instanceof ConfirmationTraining) {
            throw new \Exception('ConfirmationTraining not found.');
        }

        $confirmationTraining->{$parameterSave} = $args[$parameterSave];
        $confirmationTraining->save();

        return $confirmationTraining;
    }
}
