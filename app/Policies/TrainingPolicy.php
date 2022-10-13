<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-training');
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-training');
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('delete-training');
    }
}
