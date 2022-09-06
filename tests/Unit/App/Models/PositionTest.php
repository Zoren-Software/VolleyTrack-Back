<?php

namespace Tests\Unit\App\Models;

use App\Models\Position;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\TestCase;
use Spatie\Activitylog\LogOptions;

class PositionTest extends TestCase
{
    /**
     * A basic unit test relation users.
     *
     * @return void
     */
    public function test_users()
    {
        $position = new Position();
        $this->assertInstanceOf(BelongsToMany::class, $position->users());
    }

    /**
     * A basic unit test relation user.
     *
     * @return void
     */
    public function test_user()
    {
        $position = new Position();
        $this->assertInstanceOf(BelongsTo::class, $position->user());
    }

    /**
     * A basic unit test relation getActivitylogOptions.
     *
     * @return void
     */
    public function test_get_activitylog_options()
    {
        $user = new Position();
        $this->assertInstanceOf(LogOptions::class, $user->getActivitylogOptions());
    }
}
