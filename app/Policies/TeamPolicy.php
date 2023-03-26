<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new team instance.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-team');
    }

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
