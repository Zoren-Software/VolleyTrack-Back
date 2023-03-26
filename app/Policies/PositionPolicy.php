<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PositionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new position instance.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-position');
    }

    /**
     * Edit a position instance.
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-position');
    }

    /**
     * Delete a position instance.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-position');
    }

    /**
     * View a position instance.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('edit-position') || $user->hasPermissionTo('view-position');
    }
}
