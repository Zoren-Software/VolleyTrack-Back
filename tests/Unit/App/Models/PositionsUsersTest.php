<?php

namespace Tests\Unit\App\Models;

use App\Models\PositionsUsers;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class PositionsUsersTest extends TestCase
{
    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    public function test_get_activitylog_options()
    {
        $user = new PositionsUsers();
        $this->assertInstanceOf(LogOptions::class, $user->getActivitylogOptions());
    }
}
