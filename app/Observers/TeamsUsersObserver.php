<?php

namespace App\Observers;

use App\Models\TeamsUsers;

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
