<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Position;
use Illuminate\Auth\Access\HandlesAuthorization;

class PositionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-position');
    }

    /**
     * Edit a policy instance.
     *
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-position');
    }


    /**
     * Delete a policy instance.
     *
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-position');
    }

    /**
     * View a policy instance.
     *
     * @return bool
     */
    public function view(User $user, Position $position): bool
    {
        return $user->hasPermissionTo('edit-position') || $user->hasPermissionTo('view-position');
    }
}
