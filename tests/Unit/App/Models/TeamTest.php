<?php

namespace Tests\Unit\App\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;
use Spatie\Activitylog\LogOptions;

class TeamTest extends TestCase
{
    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    public function test_user()
    {
        $position = new Team();
        $this->assertInstanceOf(BelongsTo::class, $position->user());
    }

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    public function test_get_activitylog_options()
    {
        $user = new Team();
        $this->assertInstanceOf(LogOptions::class, $user->getActivitylogOptions());
    }
}
