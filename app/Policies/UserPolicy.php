<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new user instance.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-user');
    }

    /**
     * Edit a user instance.
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-user');
    }

    /**
     * Delete a user instance.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-user');
    }

    /**
     * View a user instance.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('edit-user') || $user->hasPermissionTo('view-user');
    }
}
