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
            $id = $args['id'];

            if (!is_numeric($id)) {
                throw new \RuntimeException('O ID do usuário deve ser numérico.');
            }

            /** @var User $user */
            $user = User::findOrFail((int) $id);
            $this->user = $user;
        }

        $name = $args['name'] ?? null;
        $email = $args['email'] ?? null;

        if (!is_string($name) || !is_string($email)) {
            throw new \RuntimeException('Nome e e-mail devem ser strings válidas.');
        }

        $this->user->name = $name;
        $this->user->email = $email;

        if (!empty($args['password'])) {
            if (!is_string($args['password'])) {
                throw new \RuntimeException('A senha deve ser uma string.');
            }
            $this->user->makePassword($args['password']);
        }

        $this->user->save();

        if (!isset($args['id']) &&
            ($args['sendEmailNotification'] ?? false) &&
            $this->user->email_verified_at === null
        ) {
            $tenantId = tenant('id');
            if (!is_string($tenantId)) {
                throw new \RuntimeException('Tenant ID deve ser uma string.');
            }

            $this->user->sendConfirmEmailAndCreatePasswordNotification($tenantId, false);
        }

        $this->user->updateOrNewInformation($args);

        $roleId = $args['roleId'] ?? null;
        $positionId = $args['positionId'] ?? null;

        if (!is_array($roleId)) {
            throw new \RuntimeException('O roleId deve ser um array.');
        }

        if (!is_array($positionId)) {
            throw new \RuntimeException('O positionId deve ser um array.');
        }

        $this->user->roles()->sync($roleId);
        $this->user->positions()->sync($positionId);

        $this->relationTeams($this->user, $args, $context);

        $this->user->user_id = $userLogged->id;

        $this->user->touch();

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

        $teamIds = $args['teamId'] ?? [];

        if (!is_array($teamIds)) {
            throw new \RuntimeException('O campo teamId deve ser um array de IDs.');
        }

        /** @var array<int|string> $teamIds */
        $currentTeamsIds = $user->teams()->pluck('teams.id')->toArray();

        /** @var array{attached: array<int>, detached: array<int>, updated: array<int>} $changes */
        $changes = $user->teams()->sync($teamIds);

        $removedTeamsIds = array_diff($currentTeamsIds, $teamIds);
        $addedTeamsIds = $changes['attached'];

        foreach ($removedTeamsIds as $teamId) {
            $team = Team::find($teamId);
            if ($team instanceof Team) {
                $team->touch();
                $team->user_id = $userLogged->id;
                $team->save();
            }
        }

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

        $ids = $args['id'] ?? null;

        if (!is_array($ids)) {
            throw new \RuntimeException('O campo id deve ser um array.');
        }

        /** @var array<int|string> $ids */
        foreach ($ids as $id) {
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

        if (!isset($args['password']) || !is_string($args['password'])) {
            throw new \RuntimeException('A senha fornecida é inválida.');
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
