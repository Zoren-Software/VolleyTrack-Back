<?php

namespace App\GraphQL\Mutations;

use App\Models\Team;
use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class TeamMutation
{
    private Team $team;

    public function __construct(Team $team)
    {
        $this->team = $team;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context): Team
    {
        $userLogged = $context->user();

        if (!$userLogged instanceof User) {
            throw new \Exception('User not authenticated.');
        }

        $args['user_id'] = $userLogged->id;

        if (isset($args['id'])) {
            /** @var Team $team */
            $team = Team::findOrFail($args['id']);
            $this->team = $team;
            $this->team->update($args);
        } else {
            $this->team = $this->team->create($args);
        }

        $this->team = $this->relationUsers($args, $context);

        return $this->team;
    }

    /**
     * @param  array<string, mixed>  $args
     */
    private function relationUsers(array $args, GraphQLContext $context): Team
    {
        /** @var array<int>|null $playerIds */
        $playerIds = isset($args['player_id']) && is_array($args['player_id']) ? $args['player_id'] : null;

        if ($playerIds !== null && count($playerIds) > 0) {
            /** @var \Illuminate\Support\Collection<int, int> $ids */
            $ids = $this->team->players()->pluck('users.id');

            /** @var array<int> $currentUsersIds */
            $currentUsersIds = $ids->map(fn ($id): int => (int) $id)->toArray();

            $players = [];
            $technicians = [];

            foreach ($playerIds as $playerId) {
                /** @var User $user */
                $user = User::findOrFail($playerId);

                if ($user->hasRole('technician')) {
                    $technicians[] = $playerId;
                } else {
                    $players[] = $playerId;
                }
            }

            $changesTechnicians = $this->team->technicians()->syncWithPivotValues(
                $technicians,
                ['role' => 'technician']
            );
            $this->alteracoesModificacao($args, $currentUsersIds, $changesTechnicians, $context);

            $changesPlayers = $this->team->players()->syncWithPivotValues(
                $players,
                ['role' => 'player']
            );
            $this->alteracoesModificacao($args, $currentUsersIds, $changesPlayers, $context);
        }

        return $this->team;
    }

    /**
     * @param  array<string, mixed>  $args
     * @param  array<int>  $currentUsersIds
     * @param  array<string, mixed>  $changes
     */
    private function alteracoesModificacao(array $args, array $currentUsersIds, array $changes, GraphQLContext $context): void
    {
        /** @var array<int> $playerIds */
        $playerIds = [];

        if (isset($args['player_id']) && is_array($args['player_id'])) {
            foreach ($args['player_id'] as $id) {
                if (is_int($id) || (is_string($id) && ctype_digit($id))) {
                    $playerIds[] = (int) $id;
                }
            }
        }

        /** @var array<int> $removedUsersIds */
        $removedUsersIds = array_diff($currentUsersIds, $playerIds);

        /** @var array<int> $addedUsersIds */
        $addedUsersIds = [];

        if (isset($changes['attached']) && is_array($changes['attached'])) {
            foreach ($changes['attached'] as $id) {
                if (is_int($id) || (is_string($id) && ctype_digit($id))) {
                    $addedUsersIds[] = (int) $id;
                }
            }
        }

        /** @var int|null $userId */
        $userId = $context->user()?->getAuthIdentifier();

        foreach ($removedUsersIds as $id) {
            $user = User::find($id);
            if ($user instanceof User && $userId !== null) {
                $user->touch();
                $user->user_id = $userId;
                $user->save();
            }
        }

        foreach ($addedUsersIds as $id) {
            $user = User::find($id);
            if ($user instanceof User && $userId !== null) {
                $user->touch();
                $user->user_id = $userId;
                $user->save();
            }
        }
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @return Team[]
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

        $teams = [];

        foreach ($ids as $id) {
            /** @var Team $team */
            $team = Team::findOrFail($id);
            $this->team = $team;
            $teams[] = $this->team;
            $this->team->delete();
        }

        return $teams;
    }
}
