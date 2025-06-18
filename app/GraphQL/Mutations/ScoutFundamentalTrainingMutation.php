<?php

namespace App\GraphQL\Mutations;

use App\Models\ScoutFundamentalTraining;
use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class ScoutFundamentalTrainingMutation
{
    private ScoutFundamentalTraining $scoutFundamentalTraining;

    public function __construct(ScoutFundamentalTraining $scoutFundamentalTraining)
    {
        $this->scoutFundamentalTraining = $scoutFundamentalTraining;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context): ScoutFundamentalTraining
    {
        $userLogged = $context->user();

        if (!$userLogged instanceof User) {
            throw new \Exception('User not authenticated.');
        }

        $args['user_id'] = $userLogged->id;

        if (isset($args['id'])) {
            /** @var ScoutFundamentalTraining $scoutFundamentalTraining */
            $scoutFundamentalTraining = ScoutFundamentalTraining::findOrFail($args['id']);
            $this->scoutFundamentalTraining = $scoutFundamentalTraining;
            $this->scoutFundamentalTraining->update($args);
        } else {
            $this->scoutFundamentalTraining = $this->scoutFundamentalTraining->create($args);
        }

        return $this->scoutFundamentalTraining;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @return ScoutFundamentalTraining[]
     */
    public function delete($rootValue, array $args, GraphQLContext $context): array
    {
        /** @var array<int> $ids */
        $ids = [];

        if (isset($args['id']) && is_array($args['id'])) {
            foreach ($args['id'] as $id) {
                if (is_int($id) || (is_string($id) && ctype_digit($id))) {
                    $ids[] = (int) $id;
                }
            }
        }

        $scoutFundamentalTrainings = [];

        foreach ($ids as $id) {
            /** @var ScoutFundamentalTraining $scoutFundamentalTraining */
            $scoutFundamentalTraining = ScoutFundamentalTraining::findOrFail($id);
            $this->scoutFundamentalTraining = $scoutFundamentalTraining;
            $scoutFundamentalTrainings[] = $this->scoutFundamentalTraining;
            $this->scoutFundamentalTraining->delete();
        }

        return $scoutFundamentalTrainings;
    }
}
