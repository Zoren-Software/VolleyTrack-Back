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
     * @return void
     */
    public function test_user()
    {
        $team = new Team();
        $this->assertInstanceOf(BelongsTo::class, $team->user());
    }

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    public function test_get_activitylog_options()
    {
        $team = new Team();
        $this->assertInstanceOf(LogOptions::class, $team->getActivitylogOptions());
    }

    /**
     * A basic unit test relation players.
     *
     * @return void
     */
    public function test_players()
    {
        $team = new Team();
        $this->assertInstanceOf(BelongsToMany::class, $team->players());
    }
}
