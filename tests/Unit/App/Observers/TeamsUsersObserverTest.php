<?php

namespace Tests\Unit\App\Observers;

use App\Models\TeamsUsers;
use App\Observers\TeamsUsersObserver;
use Tests\TestCase;

class TeamsUsersObserverTest extends TestCase
{
    /**
     * Test created method
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function created()
    {
        $teamsUsersMock = $this->mock(TeamsUsers::class, function ($mock) {
            $mock->shouldReceive('updateRoleInRelationship')
                ->once()
                ->andReturn(true);
        });
        $teamsUsersObserver = new TeamsUsersObserver;
        $teamsUsersObserver->created($teamsUsersMock);
    }

    /**
     * Test updated method
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function updated()
    {
        $teamsUsersMock = $this->mock(TeamsUsers::class, function ($mock) {
            $mock->shouldReceive('updateRoleInRelationship')
                ->once()
                ->andReturn(true);
        });
        $teamsUsersObserver = new TeamsUsersObserver;
        $teamsUsersObserver->updated($teamsUsersMock);
    }
}
