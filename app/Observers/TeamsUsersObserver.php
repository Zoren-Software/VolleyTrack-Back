<?php

namespace App\Observers;

use App\Models\TeamsUsers;

class TeamsUsersObserver
{
    /**
     * @return void
     */
    public function created(TeamsUsers $teamsUsers)
    {
        $teamsUsers->updateRoleInRelationship();
    }

    /**
     * @return void
     */
    public function updated(TeamsUsers $teamsUsers)
    {
        $teamsUsers->updateRoleInRelationship();
    }
}
