<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-user');
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-user');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-user');
    }
}
