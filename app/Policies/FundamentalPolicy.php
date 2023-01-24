<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FundamentalPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-fundamental');
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-fundamental');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-fundamental');
    }
}
