<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new team instance.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-team');
    }

    /**
     * Edit a team instance.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-team');
    }

    /**
     * Delete a team instance.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-team');
    }

    /**
     * View a team instance.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('edit-team') || $user->hasPermissionTo('view-team');
    }
}
