<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfigPolicy
{
    use HandlesAuthorization;

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-config');
    }
}
