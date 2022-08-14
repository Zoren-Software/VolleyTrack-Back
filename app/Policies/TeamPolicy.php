<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-team');
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-team');
    }
}
