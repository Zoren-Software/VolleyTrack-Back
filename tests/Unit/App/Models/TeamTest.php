<?php

namespace Tests\Unit\App\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class TeamTest extends TestCase
{
    /**
     * A basic unit test relation user.
     *
     * @test
     *
     * @return void
     */
    public function user()
    {
        $team = new Team();
        $this->assertInstanceOf(BelongsTo::class, $team->user());
    }

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @test
     *
     * @return void
     */
    public function getActivitylogOptions()
    {
        $team = new Team();
        $this->assertInstanceOf(LogOptions::class, $team->getActivitylogOptions());
    }

    /**
     * A basic unit test relation players.
     *
     * @test
     *
     * @return void
     */
    public function players()
    {
        $team = new Team();
        $this->assertInstanceOf(BelongsToMany::class, $team->players());
    }

    /**
     * A basic unit test relation technicians.
     *
     * @test
     *
     * @return void
     */
    public function technicians()
    {
        $team = new Team();
        $this->assertInstanceOf(BelongsToMany::class, $team->technicians());
    }
}
