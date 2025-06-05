<?php

namespace App\GraphQL\Mutations;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class UserMutation
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function make($rootValue, array $args, GraphQLContext $context): User
    {
        $userLogged = $context->user();

        if (!$userLogged instanceof User) {
            throw new \Exception('User not authenticated.');
        }

        if (isset($args['id'])) {
            /** @var User $user */
            $user = User::findOrFail($args['id']);
            $this->user = $user;
        }

        $this->user->name = $args['name'];
        $this->user->email = $args['email'];

        if (!empty($args['password'])) {
            $this->user->makePassword($args['password']);
        }

        $this->user->save();

        if (!isset($args['id']) && $args['sendEmailNotification'] && $this->user->email_verified_at === null) {
            $this->user->sendConfirmEmailAndCreatePasswordNotification(tenant('id'), false);
        }

        $this->user->updateOrNewInformation($args);

        $this->user->roles()->sync($args['roleId']);

        $this->user->positions()->sync($args['positionId']);

        $this->relationTeams($this->user, $args, $context);

        $this->user->user_id = $userLogged->id;

        $this->user->touch();

        // Recarrega o usuário com todos os campos necessários
        return $this->user->fresh() ?? $this->user;
    }

    /**
     * @param  array<string, mixed>  $args
     */
    private function relationTeams(User $user, array $args, GraphQLContext $context): void
    {
        $userLogged = $context->user();

        if (!$userLogged instanceof User) {
            throw new \Exception('User not authenticated.');
        }

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
            if ($team instanceof Team) {
                $team->touch();
                $team->user_id = $userLogged->id;
                $team->save();
            }
        }

        // NOTE - Atualiza o 'updated_at' dos times adicionados
        foreach ($addedTeamsIds as $teamId) {
            $team = Team::find($teamId);
            if ($team instanceof Team) {
                $team->touch();
                $team->user_id = $userLogged->id;
                $team->save();
            }
        }
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @return array<User>
     */
    public function delete($rootValue, array $args, GraphQLContext $context): array
    {
        $users = [];
        foreach ($args['id'] as $id) {
            /** @var User $user */
            $user = User::findOrFail($id);

            $this->user = $user;
            $users[] = $this->user;
            $this->user->delete();
        }

        return $users;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     */
    public function setPassword($rootValue, array $args, GraphQLContext $context): User
    {
        $user = User::where([
            'set_password_token' => $args['token'],
            'email' => $args['email'],
        ])->first();

        if (!$user) {
            throw new \Exception('Invalid token or email.');
        }

        $this->user = $user;

        $this->user->password = Hash::make($args['password']);
        $this->user->user_id = $this->user->id;
        $this->user->set_password_token = null;
        $this->user->save();

        return $this->user;
    }

    /**
     * @param  mixed  $rootValue
     * @param  array<string, mixed>  $args
     * @return array<string, string>
     */
    public function forgotPassword($rootValue, array $args, GraphQLContext $context): array
    {
        $this->user = new User;

        $this->user->sendForgotPasswordNotification($args);

        return [
            'status' => 'success',
            'message' => trans('UserForgotPassword.message_success_send_email'),
        ];
    }
}
