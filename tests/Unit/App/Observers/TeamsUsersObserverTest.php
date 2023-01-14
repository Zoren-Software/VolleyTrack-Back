<?php

namespace Tests\Unit\App\Observers;

use Tests\TestCase;
use App\Observers\TeamsUsersObserver;
use App\Models\TeamsUsers;

class TeamsUsersObserverTest extends TestCase
{
    /**
     * Test created method
     * @test
     * @return void
     */
    public function created()
    {
        $teamsUsersMock = $this->mock(TeamsUsers::class, function ($mock) {
            $mock->shouldReceive('updateRoleInRelationship')
                ->once()
                ->andReturn(true);
        });
        $teamsUsersObserver = new TeamsUsersObserver();
        $teamsUsersObserver->created($teamsUsersMock);
    }

    /**
     * Test updated method
     * @test
     * @return void
     */
    public function updated()
    {
        $teamsUsersMock = $this->mock(TeamsUsers::class, function ($mock) {
            $mock->shouldReceive('updateRoleInRelationship')
                ->once()
                ->andReturn(true);
        });
        $teamsUsersObserver = new TeamsUsersObserver();
        $teamsUsersObserver->updated($teamsUsersMock);
    }
}
