<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfirmationTrainingPolicy
{
    use HandlesAuthorization;

    /**
     * View a training instance.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-confirmation-training');
    }

    /**
     * ConfirmTraining a confirmation training instance.
     *
     * @param  array<string, mixed>  $args
     */
    public function confirmTraining(User $user, array $args): bool
    {
        return $user->hasRoleAdmin() || $user->hasRoleTechnician() || $args['player_id'] === $user->id;
    }

    /**
     * ConfirmPresence confirmation training instance.
     */
    public function confirmPresence(User $user): bool
    {
        return $user->hasRoleAdmin() || $user->hasRoleTechnician();
    }
}
