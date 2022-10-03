<?php

namespace Tests\Unit\App\Models;

use App\Models\TeamsUsers;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class TeamsUsersTest extends TestCase
{
    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    public function test_get_activitylog_options()
    {
        $user = new TeamsUsers();
        $this->assertInstanceOf(LogOptions::class, $user->getActivitylogOptions());
    }
}
