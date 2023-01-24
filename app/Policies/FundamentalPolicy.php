<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FundamentalPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new fundamental instance.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-fundamental');
    }

    /**
     * Edit a fundamental instance.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-fundamental');
    }

    /**
     * Delete a fundamental instance.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-fundamental');
    }

    /**
     * View a fundamental instance.
     * 
     * @param User $user
     * 
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('edit-fundamental') || $user->hasPermissionTo('view-fundamental');
    }
}
