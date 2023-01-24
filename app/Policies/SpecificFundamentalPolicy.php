<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecificFundamentalPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new specific fundamental instance.
     *
     * @param  User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-specific-fundamental');
    }

    /**
     * Edit a specific fundamental instance.
     *
     * @param  User  $user
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-specific-fundamental');
    }

    /**
     * Delete a specific fundamental instance.
     *
     * @param  User  $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-specific-fundamental');
    }

    /**
     * View a specific fundamental instance.
     *
     * @param  User  $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('edit-specific-fundamental') || $user->hasPermissionTo('view-specific-fundamental');
    }
}
