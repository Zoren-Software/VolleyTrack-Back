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
     * @test
     *
     * @return void
     */
    public function getActivitylogOptions()
    {
        $positionsUsers = new PositionsUsers();
        $this->assertInstanceOf(LogOptions::class, $positionsUsers->getActivitylogOptions());
    }
}
