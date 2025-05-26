<?php

namespace App\GraphQL\Mutations;

use App\Models\Team;
use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class TeamMutation
{
    private Team $team;

    private User $user;

    public function __construct(Team $team, User $user)
    {
        $this->team = $team;
        $this->user = $user;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context)
    {
        $user = $context->user();

        if (! $user instanceof User) {
            throw new \Exception('User not authenticated.');
        }

        $args['user_id'] = $user->id;

        if (isset($args['id'])) {
            $this->team = $this->team->find($args['id']);
            $this->team->update($args);
        } else {
            $this->team = $this->team->create($args);
        }

        $this->team = $this->relationUsers($args, $context);

        return $this->team;
    }

    private function relationUsers($args, $context)
    {
        if (isset($args['player_id']) && count($args['player_id']) > 0) {

            $currentUsersIds = $this->team->players()->pluck('users.id')->toArray();

            $players = [];
            $technicians = [];

            foreach ($args['player_id'] as $playerId) {
                $user = $this->user->findOrFail($playerId);

                if ($this->user->findOrFail($playerId) && $this->user->findOrFail($playerId)->hasRole('technician')) {
                    $technicians[] = $playerId;
                } else {
                    $players[] = $playerId;
                }
            }

            $changes = $this->team->technicians()->syncWithPivotValues(
                $technicians,
                [
                    'role' => 'technician',
                ]
            );
            $this->alteracoesModificacao($args, $currentUsersIds, $changes, $context);

            $changes = $this->team->players()->syncWithPivotValues(
                $players,
                [
                    'role' => 'player',
                ]
            );

            $this->alteracoesModificacao($args, $currentUsersIds, $changes, $context);
        }

        return $this->team;
    }

    private function alteracoesModificacao($args, $currentUsersIds, $changes, $context)
    {
        // NOTE - IDs dos times que foram removidos
        $removedUsersIds = array_diff($currentUsersIds, $args['player_id']);

        // NOTE - IDs dos times que foram adicionados
        $addedUsersIds = $changes['attached'];

        // NOTE - Atualiza o 'updated_at' dos usuários removidos
        foreach ($removedUsersIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->touch();
                $user->user_id = $context->user()->id;
                $user->save();
            }
        }

        // NOTE - Atualiza o 'updated_at' dos times adicionados
        foreach ($addedUsersIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                $user->touch();
                $user->user_id = $context->user()->id;
                $user->save();
            }
        }
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        $teams = [];
        foreach ($args['id'] as $id) {
            $this->team = $this->team->findOrFail($id);
            $teams[] = $this->team;
            $this->team->delete();
        }

        return $teams;
    }
}
