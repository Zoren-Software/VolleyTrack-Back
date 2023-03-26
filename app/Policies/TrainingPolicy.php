<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new training instance.
     *
     * @return void
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('edit-training');
    }

    /**
     * Edit a training instance.
     */
    public function edit(User $user): bool
    {
        return $user->hasPermissionTo('edit-training');
    }

    /**
     * Delete a training instance.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo('edit-training');
    }

    /**
     * View a training instance.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('edit-training') || $user->hasPermissionTo('view-training');
    }
}
