<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScoutFundamentalTrainingPolicy
{
    use HandlesAuthorization;

    /**
     * Edit a team instance.
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-team');
    }

    /**
     * Delete a team instance.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-team');
    }

    /**
     * View a team instance.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('edit-team') || $user->hasPermissionTo('view-team');
    }
}
