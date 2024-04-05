<?php

namespace App\GraphQL\Mutations;

use App\Models\Team;
use App\Models\User;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Illuminate\Support\Facades\Mail;
use App\Mail\User\ConfirmEmailAndCreatePasswordMail;

final class UserMutation
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
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

        // TODO - Fazer aqui uma verificação para ver se o e-mail já foi confirmado

        $this->user->generateEmailVerificationToken();

        Mail::to($this->user->email)->send(new ConfirmEmailAndCreatePasswordMail($this->user, tenant('id')));

        $this->user->updateOrNewInformation($args);

        $this->user->roles()->sync($args['roleId']);

        $this->user->positions()->sync($args['positionId']);

        $this->relationTeams($this->user, $args, $context);

        $this->user->user_id = $context->user()->id;

        $this->user->touch();

        return $this->user;
    }

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
     * @param  null  $_
     * @param  array<string, mixed>  $args
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
}
