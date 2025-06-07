<?php

namespace Tests\Unit\App\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class TeamTest extends TestCase
{
    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function user()
    {
        $team = new Team;
        $this->assertInstanceOf(BelongsTo::class, $team->user());
    }

    /**
     * A basic unit test relation confirmationsTraining.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function confirmations_training()
    {
        $team = new Team;
        $this->assertInstanceOf(HasMany::class, $team->confirmationsTraining());
    }

    /**
     * A basic unit test relation technicians.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function technicians()
    {
        $team = new Team;
        $this->assertInstanceOf(BelongsToMany::class, $team->technicians());
    }

    /**
     * A basic unit test relation players.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function players()
    {
        $team = new Team;
        $this->assertInstanceOf(BelongsToMany::class, $team->players());
    }

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function get_activitylog_options()
    {
        $team = new Team;
        $this->assertInstanceOf(LogOptions::class, $team->getActivitylogOptions());
    }
}
