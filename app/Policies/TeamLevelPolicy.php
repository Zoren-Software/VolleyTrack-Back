<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamLevelPolicy
{
    use HandlesAuthorization;

    /**
     * View a team instance.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view-team-levels');
    }
}
