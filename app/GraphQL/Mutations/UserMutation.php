<?php

namespace App\GraphQL\Mutations;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class UserMutation
{
    private ?User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param  mixed  $rootValue
     * @return [type]
     */
    public function make($rootValue, array $args, GraphQLContext $context)
    {
        if (isset($args['id'])) {
            $this->user = $this->user->findOrFail($args['id']);
        }

        $this->user->name = $args['name'];
        $this->user->email = $args['email'];

        if (isset($args['password']) && $args['password'] !== null && $args['password'] !== '') {
            $this->user->makePassword($args['password']);
        }

        $this->user->save();

        if (!isset($args['id'])) {
            $this->user->sendConfirmEmailAndCreatePasswordNotification();
        }

        $this->user->updateOrNewInformation($args);

        $this->user->roles()->sync($args['roleId']);

        $this->user->positions()->sync($args['positionId']);

        $this->relationTeams($this->user, $args, $context);

        $this->user->user_id = $context->user()->id;

        $this->user->touch();

        return $this->user;
    }

    /**
     * @param  mixed  $user
     * @param  mixed  $args
     * @param  mixed  $context
     * @return [type]
     */
    private function relationTeams($user, $args, $context)
    {
        // NOTE - Obtém os IDs dos times atualmente associados ao usuário
        $currentTeamsIds = $user->teams()->pluck('teams.id')->toArray();

        // NOTE - Sincroniza e obtém os detalhes das alterações
        $changes = $user->teams()->sync($args['teamId']);

        // NOTE - IDs dos times que foram removidos
        $removedTeamsIds = array_diff($currentTeamsIds, $args['teamId']);

        // NOTE - IDs dos times que foram adicionados
        $addedTeamsIds = $changes['attached'];

        // NOTE - Atualiza o 'updated_at' dos times removidos
        foreach ($removedTeamsIds as $teamId) {
            $team = Team::find($teamId);
            if ($team) {
                $team->touch();
                $team->user_id = $context->user()->id;
                $team->save();
            }
        }

        // NOTE - Atualiza o 'updated_at' dos times adicionados
        foreach ($addedTeamsIds as $teamId) {
            $team = Team::find($teamId);
            if ($team) {
                $team->touch();
                $team->user_id = $context->user()->id;
                $team->save();
            }
        }
    }

    /**
     * @param  mixed  $rootValue
     * @return [type]
     */
    public function delete($rootValue, array $args, GraphQLContext $context)
    {
        $users = [];
        foreach ($args['id'] as $id) {
            $this->user = $this->user->findOrFail($id);
            $users[] = $this->user;
            $this->user->delete();
        }

        return $users;
    }

    /**
     * @param  mixed  $rootValue
     * @return [type]
     */
    public function setPassword($rootValue, array $args, GraphQLContext $context)
    {
        $this->user = User::where([
            'set_password_token' => $args['token'],
            'email' => $args['email'],
        ])->first();

        $this->user->password = Hash::make($args['password']);
        $this->user->user_id = $this->user->id;
        $this->user->set_password_token = null;
        $this->user->save();

        return $this->user;
    }
}
