<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecificFundamentalPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-specific-fundamental');
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-specific-fundamental');
    }
}
