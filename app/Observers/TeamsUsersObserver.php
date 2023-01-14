<?php

namespace App\Observers;

use App\Models\TeamsUsers;
use App\Models\User;

class TeamsUsersObserver
{
    public function created(TeamsUsers $teamsUsers)
    {
        $teamsUsers->updateRoleInRelationship();
    }

    public function updated(TeamsUsers $teamsUsers)
    {
        $teamsUsers->updateRoleInRelationship();
    }
}
