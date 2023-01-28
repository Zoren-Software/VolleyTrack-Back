<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfirmationTrainingPolicy
{
    use HandlesAuthorization;

    /**
     * View a training instance.
     *
     * @param  User  $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-confirmation-training');
    }
}
