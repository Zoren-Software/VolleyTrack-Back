<?php

namespace App\Observers\Logs;

use App\Models\UserInformation;

class UserInformationObserver
{
    /**
     * Handle the UserInformation "updated" event.
     *
     * @param \App\Models\UserInformation $userInformation
     * @return void
     */
    public function updated(UserInformation $userInformation)
    {
        $userInformation->user->touch();
        $userInformation->user->saveLastUserChange();
    }

    /**
     * Handle the UserInformation "created" event.
     *
     * @param \App\Models\UserInformation $userInformation
     * @return void
     */
    public function created(UserInformation $userInformation)
    {
        $userInformation->user->touch();
        $userInformation->user->saveLastUserChange();
    }
}
