<?php

namespace App\GraphQL\Mutations;

use App\Models\ConfirmationTraining;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class ConfirmationTrainingMutation
{
    private ConfirmationTraining $confirmationTraining;

    /**
     * @param ConfirmationTraining $confirmationTraining
     */
    public function __construct(ConfirmationTraining $confirmationTraining)
    {
        $this->confirmationTraining = $confirmationTraining;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return ConfirmationTraining
     */
    public function confirmTraining($rootValue, array $args, GraphQLContext $context): ConfirmationTraining
    {
        return $this->confirm('status', $args);
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @param GraphQLContext $context
     * 
     * @return ConfirmationTraining
     */
    public function confirmPresence($rootValue, array $args, GraphQLContext $context): ConfirmationTraining
    {
        return $this->confirm('presence', $args);
    }

    /**
     * @param string $parameterSave
     * @param array<string, mixed> $args
     * 
     * @return ConfirmationTraining
     */
    public function confirm($parameterSave, array $args): ConfirmationTraining
    {
        if (isset($args['id'])) {
            $this->confirmationTraining = $this->confirmationTraining->find($args['id']);
        } elseif (isset($args['training_id']) && isset($args['player_id'])) {
            $this->confirmationTraining = $this->confirmationTraining
                ->where('training_id', $args['training_id'])
                ->where('player_id', $args['player_id'])->first();
        }

        $this->confirmationTraining->$parameterSave = $args[$parameterSave];
        $this->confirmationTraining->save();

        return $this->confirmationTraining;
    }
}
