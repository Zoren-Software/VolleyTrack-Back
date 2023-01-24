<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PositionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-position');
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-position');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-position');
    }
}
