<?php

namespace App\Observers;

use App\Models\TeamsUsers;

class TeamsUsersObserver
{
    /**
     * @param TeamsUsers $teamsUsers
     * 
     * @return void
     */
    public function created(TeamsUsers $teamsUsers)
    {
        $teamsUsers->updateRoleInRelationship();
    }

    /**
     * @param TeamsUsers $teamsUsers
     * 
     * @return void
     */
    public function updated(TeamsUsers $teamsUsers)
    {
        $teamsUsers->updateRoleInRelationship();
    }
}
