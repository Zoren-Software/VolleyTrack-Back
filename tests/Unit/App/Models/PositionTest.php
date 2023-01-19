<?php

namespace Tests\Unit\App\Models;

use App\Models\Position;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Tests\TestCase;

class PositionTest extends TestCase
{
    /**
     * A basic unit test relation users.
     *
     * @test
     *
     * @return void
     */
    public function users()
    {
        $position = new Position();
        $this->assertInstanceOf(BelongsToMany::class, $position->users());
    }

    /**
     * A basic unit test relation user.
     *
     * @test
     *
     * @return void
     */
    public function user()
    {
        $position = new Position();
        $this->assertInstanceOf(BelongsTo::class, $position->user());
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
        $position = new Position();
        $this->assertInstanceOf(LogOptions::class, $position->getActivitylogOptions());
    }
}
